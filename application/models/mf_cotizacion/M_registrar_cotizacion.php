<?php
class M_registrar_cotizacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getItemplanPreRegistro($eccSession){
        $Query = ' SELECT 	po.itemplan,	p.proyectoDesc,	sp.subProyectoDesc,	e.empresaColabDesc,	c.jefatura, 
                            c.region,	ep.estadoPlanDesc, pco.estado, DATE_FORMAT(pco.fecha_creacion, "%d/%m/%Y") as fecha_creacion,
                            pco.ruta_pdf, po.idCentral, pco.responsable, pco.path_pdf_to_cotiza, 
                            FORMAT(pco.monto,2) as monto_mo, 
                            FORMAT(pco.monto_mat,2) as monto_mat,
                            FORMAT((pco.monto+pco.monto_mat),2) as monto_total
                     FROM	planobra_cotizacion pco, 
                    		subproyecto sp, 
                            proyecto p,						
                            estadoplan ep,
							planobra po 
				LEFT JOIN  	central c ON	po.idCentral = c.idCentral
				LEFT JOIN  	empresacolab e ON	c.idEmpresaColab 	= e.idEmpresaColab
                    WHERE 	po.itemplan 		= pco.itemplan
                    AND 	po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= p.idProyecto
                    AND 	po.idEstadoPlan 	= ep.idEstadoPlan
					AND     po.paquetizado_fg = null
                    AND     pco.estado          !=   6
                    AND 	po.idEstadoPlan 	= '.ESTADO_PLAN_PRE_REGISTRO; 
        if($eccSession != 0 && $eccSession !=6){
            $Query .= ' AND  c.idEmpresaColab = '.$eccSession.' 
                        AND  pco.responsable = 2';
        }else{
            $Query .= ' AND  pco.responsable IN (1,2)
                        AND  pco.estado  !=   3';
        }
		
		$Query .= '  UNION ALL   
					SELECT 	po.itemplan,	p.proyectoDesc,	sp.subProyectoDesc,	e.empresaColabDesc,	c.jefatura, 
                            c.region,	ep.estadoPlanDesc, pco.estado, DATE_FORMAT(pco.fecha_creacion, "%d/%m/%Y") as fecha_creacion,
                            pco.ruta_pdf, po.idCentral, pco.responsable, pco.path_pdf_to_cotiza, 
                            FORMAT(pco.monto,2) as monto_mo, 
                            FORMAT(pco.monto_mat,2) as monto_mat,
                            FORMAT((pco.monto+pco.monto_mat),2) as monto_total
                     FROM	planobra_cotizacion pco, 
                    		subproyecto sp, 
                            proyecto p,						
                            estadoplan ep,
							planobra po 
				LEFT JOIN  	pqt_central c ON	po.idCentralPqt = c.idCentral
				LEFT JOIN  	empresacolab e ON	po.idEmpresaColab 	= e.idEmpresaColab
                    WHERE 	po.itemplan 		= pco.itemplan
                    AND 	po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= p.idProyecto
                    AND 	po.idEstadoPlan 	= ep.idEstadoPlan
					AND     po.paquetizado_fg IN (1,2)
                    AND     pco.estado          !=   6
                    AND 	po.idEstadoPlan 	= '.ESTADO_PLAN_PRE_REGISTRO; 
        if($eccSession != 0 && $eccSession !=6){
            $Query .= ' AND  po.idEmpresaColab = '.$eccSession.' 
                        AND  pco.responsable = 2';
        }else{
            $Query .= ' AND  pco.responsable IN (1,2)
                        AND  pco.estado  !=   3';
        }
        
          
	    $result = $this->db->query($Query,array());	   
		log_message('error', $this->db->last_query());
	    return $result;
	}
		
	function saveFileCotizacion($itemplan, $pathFile, $monto_mo, $monto_mat){	   
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
        
            $this->db->trans_begin();
            $dataimg = array (
                    "usuario_registro_pdf"  => $this->session->userdata('userSession'),
                    "fecha_registro_pdf"    => date("Y-m-d H:i:s"),
                    "ruta_pdf"              => $pathFile,
                    "estado"                => 2, //con pdf
                    "monto"                 => $monto_mo,
                    "monto_mat"             => $monto_mat
                );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra_cotizacion',$dataimg);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
	}
	
	function enviarCotizacion($itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $dataimg = array (
	            "usuario_envio_cotizacion"  => $this->session->userdata('userSession'),
	            "fecha_envio_cotizacion"    => date("Y-m-d H:i:s"),	            
	            "estado"                => 3//cotizacion enviada
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_cotizacion',$dataimg);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function sendCotiToEECC($itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $dataimg = array (	            
	            "responsable"                => 2//cotizacion enviada
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_cotizacion',$dataimg);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updatePlanObraCentral($itemplan, $idCentral, $idZonal, $idEECC){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $dataimg = array (
	            "idCentral"        =>  $idCentral,
	            "idZonal"          =>  $idZonal,
	            "idEmpresaColab"   =>  $idEECC
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra',$dataimg);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updateMonto($itemplan, $monto, $monto_mat){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $dataimg = array (
	            'monto'    => $monto,
	            'monto_mat' => $monto_mat//cotizacion enviada
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_cotizacion',$dataimg);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}