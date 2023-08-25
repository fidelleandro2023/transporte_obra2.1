
<?php

/**
 * Description of M_registro
 *
 * @author ivan.more
 */
class M_gestion_itemfault extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getDataDiseno($inputItemfaut, $idEstadoItemfault) {
        $sql = " SELECT DISTINCT
                        i.itemfault,
                        i.nombre,
                        e.idEstacion,
                        e.estacionDesc,
                        s.servicioDesc,
                        se.elementoDesc,
                        es.estadoPlanDesc,
                        emp.empresacolabDesc AS eecc,
                        es.idEstadoPlan,
                        i.fecha_registro
                   FROM servicio_elemento_estacion see,
                        estacionarea ea,
                        area a,
                        itemfault i,
                        estacion e,
                        servicio s,
                        servicio_elemento se,
                        estadoplan es,
                        empresacolab emp
                  WHERE see.idEstacionArea = ea.idEstacionArea
                    AND ea.idArea = a.idArea
                    AND i.idServicioElemento = see.idServicioElemento
                    AND ea.idEstacion = e.idEstacion
                    AND se.idServicio = s.idServicio
                    AND se.idServicioElemento = see.idServicioElemento
                    AND es.idEstadoPlan = i.idEstadoItemfault
                    AND emp.idEmpresaColab = i.idEmpresaColab
                    AND i.itemfault = ?
					AND i.idEstadoItemfault = ?";
        $result = $this->db->query($sql, array($inputItemfaut, $idEstadoItemfault));
        return $result->result();
    }
	
	function updateEjecDiseno($dataInsert, $dataUpdate, $itemfault){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemfault', $itemfault);
	        $this->db->update('itemfault',$dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
                $this->db->insert('itemfault_diseno', $dataInsert);

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
                }else{
                    $data['error']    = EXIT_SUCCESS;
                    $data['msj']      = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getLastEstadoByItemPlan($itemPlan){
	    $Query = 'SELECT *
                    FROM control_estado_itemplan
					WHERE id_control_estado_itemplan = 
	                   (SELECT MAX(id_control_estado_itemplan) FROM control_estado_itemplan WHERE itemplan = ?)
					  AND idEstadoPlan <> 19;';
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result;
	}
	
	function getValidaPoMatMo($itemfault, $idEstacion) {
		$sql = " SELECT SUM(CASE WHEN a.tipoArea = 'MO' THEN 1 ELSE 0 END) flg_mo,
						SUM(CASE WHEN a.tipoArea = 'MAT' THEN 1 ELSE 0 END) flg_mat
				   FROM itemfault_po ip,
						area a
				  WHERE a.idArea = ip.idArea
				    AND ip.itemfault  = ?
					AND ip.idEstacion = ?";
		$result = $this->db->query($sql, array($itemfault, $idEstacion));
	    return $result->row_array();
	}
}