<?php

class M_main_run extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    public function getInfoPoByCodigoPo($codigo_po) {
        $Query = "SELECT * FROM planobra_po WHERE codigo_po = ?";
        $result = $this->db->query($Query, array($codigo_po));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getInfoCodigoMaterial($codigo){
        $Query = "SELECT id_material, descrip_material, costo_material, paquetizado
        	       FROM material
	               WHERE   (paquetizado = 1 OR flg_tipo = 1)
	               AND id_material = ? LIMIT 1";
        $result = $this->db->query($Query,array($codigo));
        //log_message('error', $this->db->last_query());
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getCostoMaxMatAndCostoMByItemplan($itemplan){
        $Query = "SELECT
                        pmat.*, po.costo_unitario_mo
                    FROM
                        planobra po
                    LEFT JOIN
                        pqt_precio_max_mat_x_subpro pmat ON po.idSubProyecto = pmat.idSubProyecto
                    WHERE
                        po.itemplan = ? LIMIT 1";
        $result = $this->db->query($Query,array($itemplan));
        //log_message('error', $this->db->last_query());
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigo_partida){
        $Query = "SELECT
					pa.idActividad,
					pa.codigo, pa.descripcion,  tp.descripcion as descPrecio, pa.baremo, pq.costo
				FROM
					partidas pa,
					pqt_tipo_preciario tp,
					pqt_preciario pq,
					planobra po,
					pqt_central c
				WHERE
					pa.idPrecioDiseno = tp.id
				AND tp.id = pq.idTipoPreciario
				AND pa.codigo = ?
				AND po.idEmpresaColab = pq.idEmpresaColab
				AND	po.idCentralPqt = c.idCentral
				AND po.itemplan = ?
				AND pq.tipoJefatura = (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END)
	                LIMIT 1";
        $result = $this->db->query($Query,array($codigo_partida, $itemplan));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function actualizarFerreteriasPqt($itemplanMatPadre, $itemplanMatDetalle, $detallePoMo, $listaPo){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert_batch('itemplan_material_x_estacion_pqt', $itemplanMatPadre);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en itemplan_material_x_estacion_pqt');
            }else{
                $this->db->insert_batch('itemplan_material_x_estacion_pqt_detalle', $itemplanMatDetalle);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en itemplan_material_x_estacion_pqt_detalle');
                }else{
                    $this->db->insert_batch('planobra_po_detalle_mo', $detallePoMo);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en planobra_po_detalle_mo');
                    }else{
                        foreach($listaPo as $po){
                            $sql = "update planobra_po set costo_total = (select sum(monto_final)
                                        from planobra_po_detalle_mo where codigo_po = '".$po."')
                                        where codigo_po = '".$po."'";
                            $this->db->query($sql, array());
                            if($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Error al actualizar en planobra_po.');
                            }
                        }
                        if($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Error al actualizar la informacion.');
                        } else {
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualizo correctamente!';
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
}
