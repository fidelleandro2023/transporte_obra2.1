
<?php

class M_reg_itemplan_madre extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function getDataTablaItemMadre() {
        $sql = " SELECT proyectoDesc,
                        subproyectoDesc AS subDesc,
                        i.itemplan_m,
                        i.fecha_registro,
                        i.nombre
                   FROM itemplan_madre i,
                        proyecto p,
                        subproyecto s
                  WHERE s.idProyecto = p.idProyecto
                    AND i.idProyecto = p.idProyecto
                    AND i.idSubProyecto = s.idSubProyecto";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function regItemMadre($objReg) {
        $this->db->insert('itemplan_madre', $objReg);
        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No ingreso el itemplan de forma correcta, verificar si ya lo tiene registrado.';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se ingreso correctamente.';
        }
        return $data;
    }

    function getPepItemplanMadre($cmbSubProyecto) {
        $sql = " SELECT
                 *
                FROM
                bolsa_pep
                WHERE
                tipo_pep = 2
                AND idSubProyecto = '$cmbSubProyecto'
                AND idEstacion = 1
                AND estado = 1;";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function OCregistroItemplanMadre($itemplanM, $idpep) {
        $sql = "SELECT createOCByItemplanMadre('$itemplanM','$idpep')";
        $this->db->query($sql);
        return true;
    }

    function getSAPdetalle($pep) {
        $Query = "SELECT * FROM sap_detalle WHERE pep1='$pep';";
        $result = $this->db->query($Query, array());
        return $result->result_array();
    }

    function obtenerUltimoRegistro() {
        $Query = " SELECT * from itemplan_madre order by itemplan_m desc limit 1;";
        $result = $this->db->query($Query, array());
        $itemplan = $result->row()->itemplan_m;
        return $itemplan;
    }

    //--

    function saveDetalleObraPublica($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->insert('itemplan_madre_detalle_obras_publicas', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el sisego_planobra');
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

    public function updateMontoPEP($idpep, $textMonto) {
        $sql = "UPDATE sap_detalle SET monto_temporal=(monto_temporal-'$textMonto') WHERE pep1='$idpep'";
        $this->db->query($sql);
        return true;
    }

}
