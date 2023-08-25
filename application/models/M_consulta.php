<?php

/**
 * Description of M_registro
 *
 * @author ivan.more
 */
class M_consulta extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTablaConsulta($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento) {
        $sql = "SELECT 
                fault.itemfault,
                fault.nombre,
                ser.servicioDesc,
                elemen.elementoDesc,
                emp.empresaColabDesc,
                fault.fecha_registro,
                est.estadoPlanDesc,
                est.idEstadoPlan,
                fault.pdf_propuesta_uno,
                fault.idSituacion,
                fault.montoMO,
                fault.montoMAT
                FROM itemfault fault
                INNER JOIN servicio ser ON ser.idServicio = fault.idServicio
                INNER JOIN servicio_elemento elemen on elemen.idServicioElemento = fault.idServicioElemento
                INNER JOIN empresacolab emp on emp.idEmpresaColab = fault.idEmpresaElec
                INNER JOIN estadoplan est on est.idEstadoPlan = fault.idEstadoItemfault
                WHERE ser.servicioDesc LIKE '%$selectServicio%' AND elemen.elementoDesc LIKE '%$selectElementoServicio%'
                AND fault.fecha_registro LIKE '%$inputcreacion%' AND fault.itemfault LIKE '%$inputItemfaut%'
                AND fault.nombre LIKE '%$inputNombrePlan%' AND fault.idEstadoItemfault LIKE '%$selectEstado%'
                AND fault.idGerencia  LIKE '%$selectGerencia%' AND fault.idEvento LIKE '%$selectEvento%' 
                AND fault.idSubEvento LIKE '%$selectSubEvento%'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getTablaBandejaPreApro($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento) {
        $sql = "SELECT 
                fault.itemfault,
                fault.nombre,
                ser.servicioDesc,
                elemen.elementoDesc,
                emp.empresaColabDesc,
                fault.fecha_registro,
                est.estadoPlanDesc,
				emp.idEmpresaColab,
                est.idEstadoPlan,
				po.codigo_itemfault_po,
				FORMAT(po.costo_total,2) as costo_total
                FROM itemfault_po po
		INNER JOIN itemfault fault on fault.itemfault = po.itemfault
                INNER JOIN servicio ser ON ser.idServicio = fault.idServicio
                INNER JOIN servicio_elemento elemen on elemen.idServicioElemento = fault.idServicioElemento
                INNER JOIN empresacolab emp on emp.idEmpresaColab = fault.idEmpresaElec
                INNER JOIN estadoplan est on est.idEstadoPlan = fault.idEstadoItemfault
                WHERE ser.servicioDesc LIKE '%$selectServicio%' AND elemen.elementoDesc LIKE '%$selectElementoServicio%'
                AND fault.fecha_registro LIKE '%$inputcreacion%' AND fault.itemfault LIKE '%$inputItemfaut%'
                AND fault.nombre LIKE '%$inputNombrePlan%' AND fault.idEstadoItemfault LIKE '%$selectEstado%'
                AND fault.idGerencia  LIKE '%$selectGerencia%' AND fault.idEvento LIKE '%$selectEvento%' 
                AND fault.idSubEvento LIKE '%$selectSubEvento%'
				AND po.flg_tipo_area = 1
				AND idEstadoItemfault NOT IN (1,2)";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEstado() {
        $sql = "SELECT * FROM estadoitemfault";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function AprobarDiseno($itemfault, $dataUpdate) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemfault', $itemfault);
            $this->db->update('itemfault', $dataUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function actualizarPropuesta($itemfault, $dataUpdate) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemfault', $itemfault);
            $this->db->update('itemfault', $dataUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el itemfault.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getDataDiseno($inputItemfaut) {
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
                        i.fecha_registro,
                        (CASE WHEN i.idEstadoItemfault = '0' THEN 1 ELSE NULL END) as has_pen,
                        (CASE WHEN i.idEstadoItemfault = '1' THEN 1 ELSE NULL END) as has_fin,
                        (CASE WHEN i.idEstadoItemfault = '2' THEN 1 ELSE NULL END) as has_vali,
                        GROUP_CONCAT(a.idArea,'|',a.areaDesc,'|',tipoArea) concat_areas
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
                    AND emp.idEmpresaColab = i.idEmpresaElec
                    AND i.itemfault = ?";
        $result = $this->db->query($sql, array($inputItemfaut));
        return $result->result();
    }

    public function getAllEstaciones($item) {
        $Query = "SELECT 
                    item.itemfault,
                    estacion.estacionDesc,
                    are.areaDesc,
                    esta.idServicioElemento,
                    estacion.idEstacion,
                    (CASE WHEN item.idEstadoItemfault = '0' THEN 1 ELSE NULL END) as has_pen,
                    (CASE WHEN item.idEstadoItemfault = '1' THEN 1 ELSE NULL END) as has_fin,
                    (CASE WHEN item.idEstadoItemfault = '2' THEN 1 ELSE NULL END) as has_vali
                    FROM itemfault item
                    INNER JOIN servicio_elemento_estacion esta on esta.idServicioElemento = item.idServicioElemento
                    INNER JOIN estacionarea area on area.idEstacionArea = esta.idEstacionArea
                    INNER JOIN estacion estacion on estacion.idEstacion = area.idEstacion
                    INNER JOIN area are on are.idArea = area.idArea
                    WHERE item.itemfault=?";
        $result = $this->db->query($Query, array($item));
        //log_message('error', $this->db->last_query());
        return $result;
    }

    function getItemfaultPo($itemfault, $idArea, $idEstacion) {
        $sql = "SELECT codigo_itemfault_po as codigo_po,
                        itemfault,
                        CASE WHEN estado_po = 1 THEN '#FF0000'
                            WHEN estado_po = 2 THEN '#1CDDC5'
                            WHEN estado_po = 3 THEN '#1CDDC5'
                            WHEN estado_po = 4 THEN '#78E900'
                            WHEN estado_po = 5 THEN '#767680'
                            WHEN estado_po = 6 THEN '#F7FA07'
                            WHEN estado_po IN (7,8) THEN '#steelblue'
                            ELSE 'white' END fondo
                  FROM itemfault_po
                 WHERE itemfault = ?
                   AND idArea    = ?
                   AND idEstacion = ?";
        $result = $this->db->query($sql, array($itemfault, $idArea, $idEstacion));
        return $result->result_array();
    }

    function getCostosItemfault($itemfault) {
        $sql = "SELECT montoMat, 
		               montoMo
				  FROM itemfault
				WHERE itemfault = ?";
        $result = $this->db->query($sql, array($itemfault));
        return $result->row_array();
    }

    /** czavalacas ** */
    public function getTablaBandejaPreAproByItemplan($itemplan) {
        $sql = "SELECT 
                fault.itemfault,
                fault.nombre,
                ser.servicioDesc,
                elemen.elementoDesc,
                emp.empresaColabDesc,
                fault.fecha_registro,
                est.estadoPlanDesc,
                est.idEstadoPlan,
				po.codigo_itemfault_po,
				po.cuenta_opex,
				po.area_funcional,
				po.cuenta_seco,
				FORMAT(po.costo_total,2) as costo_total
                FROM itemfault_po po
		INNER JOIN itemfault fault on fault.itemfault = po.itemfault
                INNER JOIN servicio ser ON ser.idServicio = fault.idServicio
                INNER JOIN servicio_elemento elemen on elemen.idServicioElemento = fault.idServicioElemento
                INNER JOIN empresacolab emp on emp.idEmpresaColab = fault.idEmpresaElec
                INNER JOIN estadoplan est on est.idEstadoPlan = fault.idEstadoItemfault
                WHERE  fault.itemfault = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->result();
    }

    /*     * ******************************************************************** */

    public function getTablaPreApro($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento) {
        $sql = "SELECT 
                fault.itemfault,
                fault.nombre,
                ser.servicioDesc,
                elemen.elementoDesc,
                emp.empresaColabDesc,
                fault.fecha_registro,
                est.estadoPlanDesc,
                est.idEstadoPlan,
                fault.pdf_propuesta_uno,
                fault.idSituacion,
                fault.montoMO,
                fault.montoMAT
                FROM itemfault fault
                INNER JOIN servicio ser ON ser.idServicio = fault.idServicio
                INNER JOIN servicio_elemento elemen on elemen.idServicioElemento = fault.idServicioElemento
                INNER JOIN empresacolab emp on emp.idEmpresaColab = fault.idEmpresaElec
                INNER JOIN estadoplan est on est.idEstadoPlan = fault.idEstadoItemfault
                WHERE NOT est.idEstadoPlan IN(1,2) AND ser.servicioDesc LIKE '%$selectServicio%' AND elemen.elementoDesc LIKE '%$selectElementoServicio%'
                AND fault.fecha_registro LIKE '%$inputcreacion%' AND fault.itemfault LIKE '%$inputItemfaut%'
                AND fault.nombre LIKE '%$inputNombrePlan%' AND fault.idEstadoItemfault LIKE '%$selectEstado%'
                AND fault.idGerencia  LIKE '%$selectGerencia%' AND fault.idEvento LIKE '%$selectEvento%' 
                AND fault.idSubEvento LIKE '%$selectSubEvento%'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getDataSapMat($codigo_po) {
        $sql = "SELECT ppod.codigo_itemfault_po,
						ppod.codigo_material,
						jse.codCentro,
						ppod.cantidad_ingreso,
						'L' AS t,
						jse.codAlmacen,
						DATE_FORMAT(CURDATE(), '%d.%m.%Y') AS fecha
				   FROM (
				        itemfault_po ppo,
						itemfault_po_detalle ppod,
						itemfault po,
						pqt_central c
						) 
						LEFT JOIN 
						(
						jefatura_sap jsap,
						jefatura_sap_x_empresacolab jse) ON (jse.idEmpresaColab = ppo.idEmpresaColab
						 AND jsap.idJefatura = jse.idJefatura
						 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
						 )
						
				WHERE ppo.codigo_itemfault_po = ppod.codigo_itemfault_po
					AND ppo.itemfault = po.itemfault
					AND po.idCentral = c.idCentral
					AND ppo.codigo_itemfault_po = ?";
        $result = $this->db->query($sql, array($codigo_po));
        _log($this->db->last_query());
        return $result->result();
    }

    function actualizarVr($codigo_po, $itemfault, $arrayDataPo, $arrayLogPo) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('codigo_itemfault_po', $codigo_po);
            $this->db->update('itemfault_po', $arrayDataPo);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el VR.');
            } else {
                $this->db->insert('log_itemfault_po', $arrayLogPo);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al ingresar el log de la PO.');
                } else {
                    $this->db->where('itemfault', $itemfault);
                    $this->db->update('itemfault', array('idEstadoItemfault' => 3));

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Hubo un error al actualizar el itemfault.');
                    } else {
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se actualizo correctamente!';
                        $this->db->trans_commit();
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    /////////////
    public function UpdateTranss($codigoPo) {
        $sql = "UPDATE transaccionOpex SET estadoTransaccion=2 WHERE codigo_itemfault_po='$codigoPo'";
        $this->db->query($sql);
        return true;
    }

    public function getIdOpex($codigoPo) {
        $sql = "SELECT * FROM transaccionOpex WHERE codigo_itemfault_po='$codigoPo'";
        $result = $this->db->query($sql);
        if ($result->row()->idOpex) {
            return $result->row()->idOpex;
        } else {
            return 0;
        }
    }
    
    public function getMontoOpex($codigoPo) {
        $sql = "SELECT * FROM transaccionOpex WHERE codigo_itemfault_po='$codigoPo'";
        $result = $this->db->query($sql);
        if ($result->row()->montoTransaccion) {
            return $result->row()->montoTransaccion;
        } else {
            return 0;
        }
    }

    public function updateTransaccion($codigoPo,$MontoPO) {
        $sql = "UPDATE cuentaOpex SET monto_real=(monto_real-$MontoPO) WHERE idOpex='$codigoPo'";
        $this->db->query($sql);
        return true;
    }

}
