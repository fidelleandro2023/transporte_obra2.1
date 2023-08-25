<?php

/**
 * Description of M_registro
 *
 * @author ivan.more
 */
class M_registro extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getServicio() {
        $sql = "SELECT * FROM servicio";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getServicoElemento($id) {
        $sql = "SELECT * FROM servicio_elemento WHERE idServicio='$id'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function getEvento() {
        if ($this->session->userdata('idPerfilSession') == 48) {
            $sql = "SELECT * FROM evento WHERE NOT idEvento=3";
        } else {
            $sql = "SELECT * FROM evento";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getSubEvento($id) {
        $sql = "SELECT * FROM evento_sub WHERE idEvento='$id'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function getCorte() {
        $sql = "SELECT * FROM servicio_corte";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    public function getOptionMonto() {
        $sql = "SELECT * FROM itemfault_monto_mo";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getAllEELEC() {
        $sql = "  SELECT * FROM empresacolab;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getGerencia() {
        $sql = "SELECT * FROM gerencia";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function countItemfault() {
        $sql = "SELECT COUNT(*) as CANTIDAD FROM itemfault";
        $result = $this->db->query($sql);
        if ($result->row()->CANTIDAD) {
            return $result->row()->CANTIDAD;
        } else {
            return 0;
        }
    }

    function saveItemfault($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemfault', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el itemfault');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro correctamente correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function obtenerUltimoRegistro() {
        $Query = " SELECT itemfault from itemfault order by fecha_registro desc limit 1;";
        $result = $this->db->query($Query, array());
        $itemplan = $result->row()->itemfault;
        return $itemplan;
    }

    public function getItemplan() {
        $Query = "SELECT 
plan.itemPlan as itemplan,
plan.coordX coordenada_x,
plan.coordY coordenada_y,
pro.idProyecto,
plan.descripcion,
pro.proyectoDesc as nombre_proyecto,
sub.idSubProyecto,
sub.subProyectoDesc,
plan.fecha_creacion,
plan.nombreProyecto as nombre_proyecto,
emp.empresaColabDesc,
YEAR(plan.fecha_creacion) as ano,
plan.idEstadoPlan as idEstadoPlan,
est.estadoPlanDesc
FROM planobra plan 
INNER JOIN subproyecto sub on sub.idSubProyecto=plan.idSubProyecto
INNER JOIN proyecto pro on pro.idProyecto = sub.idProyecto
INNER JOIN estadoplan est on est.idEstadoPlan = plan.idEstadoPlan
INNER JOIN empresacolab emp on emp.idEmpresaColab = plan.idEmpresaColab
WHERE plan.idEstadoPlan in (4,9) AND NOT plan.coordX='' AND NOT plan.coordY=''";
        $result = $this->db->query($Query, array());
        return $result;
    }

    public function funcionOrden($itemfaultData, $idOpex, $idUsuario) {
        $sql = "SELECT createOCToItemfault('$itemfaultData','$idOpex','$idUsuario')";
        $this->db->query($sql);
        return true;
    }

    public function idOpex($selectEvento, $fecha) {
        $sql = "SELECT 
                eve.*,
                opex.idEstadoOpex,
                opex.anho
                FROM
                eventoOpex eve
                INNER JOIN cuentaOpex opex on opex.idOpex=eve.idOpex
                WHERE eve.idEvento='$selectEvento' AND opex.anho=YEAR('$fecha') AND opex.idEstadoOpex='1'";
        $result = $this->db->query($sql);
        $idOpex = $result->row()->idOpex;
        return $idOpex;
    }

}
