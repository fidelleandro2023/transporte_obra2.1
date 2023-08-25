<?php

class M_pqt_planobra extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    public function updDetenerPlanObra($itemplan, $arrayData) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra', $arrayData);
			_log($this->db->last_query());
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar planobra, Proceso de Detener PlanObra.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function obtEstadoAnterior($itemplan) {
        $sql = 'SELECT * FROM control_estado_itemplan WHERE id_control_estado_itemplan = (
                SELECT MAX(id_control_estado_itemplan) FROM control_estado_itemplan  WHERE itemPlan = ?)';
        $result = $this->db->query($sql, array($itemplan));
        return $result;
    }

    public function obtExceptionContratoBucle($idEmpresaColab, $idSubProyecto) {
        $sql = 'SELECT COUNT(1) count FROM excepcion_contrato_bucle WHERE idEmpresaColab = ? AND idSupProyecto = ?';
        $result = $this->db->query($sql, array($idEmpresaColab, $idSubProyecto));
        return $result->row_array();
    }

    function insertarPlanobra($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, $itemMadre, $tipo_requerimiento, $tipo_diseno, $nombre_estudio, $duracion, $acceso_cliente, $tendido_externo, $tipo_sede, $tipo_cliente, $per) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            $fechainicio = date("Y-m-d H:i:s");

            $idProvincia = 1;
            $idDepartamento = 1;
            $hasAdelanto = '0';


            $dataInsert = array(
                "itemPlan" => $itemplan,
                "nombreProyecto" => strtoupper($nombreplan),
                "coordX" => $cordx,
                "coordY" => $cordy,
                "indicador" => $indicador,
                "cantidadTroba" => intval($cantidadTroba),
                "uip" => intval($uip),
                "fechaInicio" => $fechaInicio,
                "idEstadoPlan" => intval($estadoplan),
                "idFase" => intval($fase),
                "idCentral" => (($idCentral == '') ? null : intval($idCentral)),
                "idEmpresaElec" => intval($eelec),
                "idProvincia" => intval($idProvincia),
                "idDepartamento" => intval($idDepartamento),
                "idSubProyecto" => intval($idSubproy),
                "idZonal" => intval($idzonal),
                "idEmpresaColab" => intval($eecc),
                "hasAdelanto" => $hasAdelanto,
                "fecha_creacion" => $fechainicio,
                "has_cotizacion" => $has_coti,
                "itemPlanPE" => $itemMadre,
                "operador" => $tipo_requerimiento,
                "tipo_diseno" => $tipo_diseno,
                "nombre_estudio" => $nombre_estudio,
                "duracion" => $duracion,
                "acceso_cliente" => $acceso_cliente,
                "tendido_externo" => $tendido_externo,
                "tipo_sede" => $tipo_sede,
                "tipo_cliente" => $tipo_cliente,
                "per" => $per,
                "idCentralPqt" => (($idCentral == '') ? null : intval($idCentral)),
                "paquetizado_fg" => 1,
                "usu_reg" => 'SISEGO',
                "fecha_reg" => $this->fechaActual()
            );

            $this->db->insert('planobra', $dataInsert);

            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el plan de obra');
            } else {
                $this->db->trans_commit();

                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    /////// -------- IVAN --------///

    function getItemplanMadre($codigo) {
        $Query = "SELECT * from itemplan_madre WHERE itemplan_m= ?";
        $result = $this->db->query($Query, array($codigo));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function updateIP($dataArray, $itemPlan) {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemPlan', $itemPlan);
            $this->db->update('planobra', $dataArray);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al modificar');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se modifico correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {

            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

}
