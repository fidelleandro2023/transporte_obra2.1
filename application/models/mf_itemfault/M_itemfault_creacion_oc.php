<?php
class M_itemfault_creacion_oc extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaSolOC($cod_solicitud, $itemplan, $estado) {
        $Query = "SELECT tb.*,
				(CASE WHEN tipo_solicitud = 1 THEN (SUM(CASE WHEN po.itemplan is not null  then 1 else 0 END))
					  WHEN tipo_solicitud = 2 THEN (SUM(CASE WHEN po_dev.itemplan is not null  then 1 else 0 END)) END) as numItemplan,
					  (CASE WHEN tipo_solicitud = 1 THEN FORMAT(SUM(po.montoMO),2)
							WHEN tipo_solicitud = 2 THEN FORMAT(po_dev.costo_devolucion,2) END) as costo_total
					 
							FROM (select so.*,(CASE WHEN tipo_solicitud = 1 THEN 'CREACION OC'
													WHEN tipo_solicitud = 2 THEN 'EDICION OC' END) as tipoSolicitud,p.servicioDesc
													, sp.elementoDesc, e.empresaColabDesc, 
													(CASE WHEN so.estado = 1 THEN 'PENDIENTE' 
														  WHEN so.estado = 2 THEN 'ATENDIDO' 
														  WHEN so.estado = 3 THEN 'CANCELADO' END) as estado_sol,
												CONCAT(u.nombres, ' ' ,u.ape_paterno) AS nombreCompleto,
												cop.opexDesc,cop.ceco, cop.cuenta, cop.areFuncional
							from  empresacolab e,servicio_elemento sp, servicio p, cuentaOpex cop, itemfault_solicitud_orden_compra so left join usuario u on so.usuario_valida = u.id_usuario
							where so.idEmpresaColab = e.idEmpresaColab
							and so.idServicioElemento = sp.idServicioElemento
							and sp.idServicio = p.idServicio
							and so.idCuetnaOpex = cop.idOpex
							and so.estado in (1,2,3)
							#and so.tipo_solicitud = 1
							) AS tb left join itemfault po 
							on  tb.codigo_solicitud = po.solicitud_oc
							left join itemfault po_dev 
							on  tb.codigo_solicitud = po_dev.solicitud_oc_dev
							WHERE 1  =  1";
        if($cod_solicitud  != null){
            $Query .= " and tb.codigo_solicitud = '".$cod_solicitud."'";
        }
        if($itemplan    !=  null){
            $Query .= " and (po.itemfault = '".$itemplan."'  or po_dev.itemfault = '".$itemplan."')";
        }
        if($estado  !=  null){
            $Query .= " and tb.estado= ".$estado;
        }
	   $Query .= " Group by tb.id, tb.codigo_solicitud, tb.idEmpresaColab, tb.tipo_solicitud, tb.tipoSolicitud, tb.estado, tb.usuario_valida, tb.fecha_valida, tb.fecha_creacion, tb.idServicioElemento, tb.plan, tb.pep1, tb.pep2, tb.servicioDesc, tb.elementoDesc,tb.empresaColabDesc, tb.estado_sol, tb.nombreCompleto, tb.cesta, tb.orden_compra, tb.path_oc";
        $result = $this->db->query($Query, array());
        #log_message('error', $this->db->last_query());
        return $result->result();           
    }
    
   
    public function getPtrsByHojaGestion($hoja_gestion)
    {
        $sql = "select po.*, sp.elementoDesc, FORMAT(po.montoMO,2) as limite_costo_mo, FORMAT(po.montoMAT,2) as limite_costo_mat
			    from itemfault po, servicio_elemento sp 
				where po.idServicioElemento = sp.idServicioElemento
                and po.solicitud_oc = ?";
        $result = $this->db->query($sql,array($hoja_gestion));
        log_message('error', $this->db->last_query());        
        return $result->result();
    }   

	public function getItemOrdenCompraEdicion($codigo_solicitud)
    {
        $sql = "select po.*, subProyectoDesc, FORMAT(po.costo_devolucion,2) as costo_devolucion
			    from planobra po, subproyecto sp 
				where po.idSubProyecto = sp.idSubProyecto
                and po.solicitud_oc_dev = ?";
        $result = $this->db->query($sql,array($codigo_solicitud));
        log_message('error', $this->db->last_query());        
        return $result->result();
    } 	
	
	function update_solicitud_oc($codigo_solicitud, $arrayData){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_solicitud', $codigo_solicitud);
            $this->db->update('solicitud_orden_compra', $arrayData);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar la informacion.');
            }else{              
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
	
}