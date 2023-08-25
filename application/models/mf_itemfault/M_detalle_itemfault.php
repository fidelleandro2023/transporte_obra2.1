<?php

class M_detalle_itemfault extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    public function __construct() {
        parent::__construct();
    }

    public function getDetalleItemfault($item) {
        $sql = " SELECT i.itemfault,
                        i.idServicio,
                        i.idServicioElemento,
                        i.idEstadoItemfault,
                        e.idEmpresaColab
                   FROM itemfault i,
                        empresacolab e
                  WHERE i.idEmpresaColab = e.idEmpresaColab
                    AND i.itemfault = ?";
        $result = $this->db->query($sql, array($item));
        return $result->row_array();
    }

    public function getkitMateriales($idServicioElemento, $idEstacion) {
        $sql = "SELECT m.id_material AS codigo_material,
                       m.descrip_material,
                       m.costo_material,
                       m.unidad_medida AS unidad_medida,
                       m.flg_tipo,
                      (CASE WHEN m.flg_tipo = 1 THEN 'BUCLE'
                            WHEN m.flg_tipo = 0 THEN 'NO BUCLE'
                        ELSE 'SIN ESTADO' END) AS tipo_material,
                        (ROUND(km.cantidad_kit,0)) AS cant_kit_material
                 FROM material m,
                      kit_material_itemfault km
                WHERE m.id_material = km.id_material
                  #AND m.estado_material != 'Phase out'
                  AND km.idServicioElemento = ?
                  AND km.idEstacion = ?  ";
        $result = $this->db->query($sql, array($idServicioElemento, $idEstacion));
        return $result->result();
    }

    public function countMatxKitItemfault($codMaterial, $idServicioElemento, $idEstacion) {
        $sql = " SELECT COUNT(id_material) AS cantidad
		           FROM kit_material_itemfault
                  WHERE idServicioElemento = ?
                    AND idEstacion         = ?
				    AND id_material        = ?";
        $result = $this->db->query($sql, array($idServicioElemento, $idEstacion, $codMaterial));
        return $result->row()->cantidad;
    }

    public function getDetalleMaterial($codMaterial, $idServicioElemento, $idEstacion) {
        $sql = " SELECT m.id_material AS codigo_material,
						m.descrip_material,
						m.costo_material,
                        m.unidad_medida AS unidad_medida,
                        m.flg_tipo,
						(CASE WHEN m.flg_tipo = 1 THEN 'BUCLE'
							  WHEN m.flg_tipo = 0 THEN 'NO BUCLE'
						 ELSE 'SIN ESTADO' END) AS tipo_material,
						(ROUND(km.cantidad_kit,0)) AS cant_kit_material,
						null factor_porcentual
				   FROM material m
		      LEFT JOIN kit_material_itemfault km ON m.id_material = km.id_material
                                                AND km.idServicioElemento = ?
                                                AND km.idEstacion = ? 
                  WHERE m.id_material = ? 
                    #AND m.estado_material != 'phase out' ";
        $result = $this->db->query($sql, array($idServicioElemento, $idEstacion, $codMaterial));
        return $result->row_array();
    }

    public function countMatPhaseOut($codMaterial) {
        $sql = " SELECT COUNT(id_material) AS cantidad
		           FROM material
                  WHERE id_material = '" . $codMaterial . "' 
                    #AND estado_material = 'phase out' ";
        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }

    function getCountPoItemfault($itemfault, $idEstacion, $idArea) {
        $sql = "SELECT COUNT(1) count 
		          FROM itemfault_po i
				 WHERE i.itemfault  = COALESCE(?, i.itemfault) 
				   AND i.idEstacion = COALESCE(?, i.idEstacion)
                   AND i.idArea = ?
				   AND i.estado_po NOT IN (7,8)";
        $result = $this->db->query($sql, array($itemfault, $idEstacion, $idArea));
        return $result->row_array()['count'];
    }

    public function insertarPOItemfault($arrayInsert) {

        $this->db->insert('itemfault_po', $arrayInsert);
        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se ingreso la PO, correctamente!!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insert&oacute; correctamente!!';
        }
        return $data;
    }

    public function insertarDetallePOItemfault($arrayInsert) {
        $this->db->insert_batch('itemfault_po_detalle', $arrayInsert);
        if ($this->db->trans_status() === FALSE) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se ingreso el detalle de la PO, correctamente!!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insert&oacute; correctamente!';
        }
        return $data;
    }

    public function insertarLOGPOItemfault($arrayInsert) {
        $this->db->insert('log_itemfault_po', $arrayInsert);
        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se ingreso el log de la PO, correctamente!!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insert&oacute; correctamente!!';
        }
        return $data;
    }

    function getCostosItemfault($itemfault) {
        $sql = "SELECT montoMat, 
		               montoMo
				  FROM itemfault
				WHERE itemfault = ?";
        $result = $this->db->query($sql, array($itemfault));
        return $result->row_array();
    }

    function insertarDetallePOMoItemfault($arrayInsert) {
        $this->db->insert_batch('itemfault_po_detalle_mo', $arrayInsert);
        if ($this->db->trans_status() === FALSE) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se ingreso el detalle de la PO, correctamente!!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insert&oacute; correctamente!';
        }
        return $data;
    }

    function getDataPo($codigo_po) {
        $sql = "SELECT ip.codigo_itemfault_po,
					   ip.itemfault,
					   pe.estado,
					   ip.costo_total,
					   s.servicioDesc,
					   se.elementoDesc,
					   e.empresaColabDesc,
					   a.areaDesc,
					   ip.vr,
					   ip.flg_tipo_area,
					   i.nombre as nom_itemfault,
					   z.zonalDesc
				  FROM itemfault_po ip,
					   itemfault i,
					   empresacolab e,
					   servicio_elemento se,
					   po_estado pe,
					   servicio s,
					   area a,
					   zonal z
				 WHERE ip.itemfault = i.itemfault
				   AND z.idZonal    = i.idZonal
				   AND e.idEmpresaColab = ip.idEmpresaColab
				   AND se.idServicioElemento = i.idServicioElemento
				   AND pe.idPoEstado = ip.estado_po
				   AND i.idServicio = s.idServicio
				   AND se.idServicio = s.idServicio
				   AND se.idEstado   = 1
				   AND s.idEstado    = se.idEstado
				   AND a.idArea      = ip.idArea
				   AND ip.codigo_itemfault_po = ?";
        $result = $this->db->query($sql, array($codigo_po));
        return $result->row_array();
    }

    function getLogPoItemfault($codigo_po) {
        $sql = "SELECT lg.codigo_iteamfault_po,
					   lg.itemfault,
					   u.nombre,
					   lg.fecha_registro,
					   pe.estado
				  FROM log_itemfault_po lg, 
					   usuario u,
					   po_estado pe
				WHERE lg.id_usuario = u.id_usuario
				  AND pe.idPoEstado = lg.estado_po
				  AND lg.codigo_iteamfault_po = ?";
        $result = $this->db->query($sql, array($codigo_po));
        return $result->result_array();
    }

    function getDetallePoMat($codigo_po) {
        $sql = "SELECT ipd.codigo_material,
					   ipd.cantidad_final,
					   ipd.costo_material,
					   m.descrip_material,
					   m.unidad_medida,
					   ROUND(ipd.cantidad_final*ipd.costo_material,2) totalxMat
				  FROM itemfault_po_detalle ipd,
					   material m
				 WHERE m.id_material = ipd.codigo_material
				   AND ipd.codigo_itemfault_po = ?";
        $result = $this->db->query($sql, array($codigo_po));
        return $result->result_array();
    }

    function getDetallePoMo($codigo_po) {
        $sql = "SELECT pa.descripcion,
					   imo.baremo,
					   imo.costo,
					   imo.cantidad_inicial,
					   imo.monto_inicial,
					   imo.cantidad_final,
					   imo.monto_final,
					   pa.codigo,
					   pd.descPrecio
				  FROM itemfault_po_detalle_mo imo,
					   partidas pa,
					   precio_diseno pd
				 WHERE imo.idPartida = pa.idActividad 
				   AND pa.idPrecioDiseno =  pd.idPrecioDiseno
				   AND imo.codigo_itemfault_po = ?";
        $result = $this->db->query($sql, array($codigo_po));
        return $result->result_array();
    }

    /////////////////////

    public function getIdOpex() {
        $sql = "SELECT * FROM cuentaOpex WHERE idEstadoOpex=1";
        $result = $this->db->query($sql);
        return $result->row()->idOpex;
    }

    public function insertarTransaccion($arrayInsert) {

        $this->db->insert('transaccionOpex', $arrayInsert);
        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se ingreso la PO, correctamente!!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insert&oacute; correctamente!!';
        }
        return $data;
    }

    public function updateTransaccion($costoTotalMat, $idOpex) {
        $sql = "UPDATE cuentaOpex SET monto_provisional=(monto_provisional-$costoTotalMat) WHERE idOpex='$idOpex'";
        $result = $this->db->query($sql);
        return true;
    }

}
