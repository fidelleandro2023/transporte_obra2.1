<?php
class M_proceso_piloto extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function registrarProceso($arrayData) {
        $this->db->insert('proceso_piloto', $arrayData);
        if($this->db->affected_rows() < 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se registro');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function registrarProcesoPilotoMotivo($arrayData) {
        $this->db->insert('proceso_piloto_x_motivo', $arrayData);

        if($this->db->affected_rows() < 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se registro');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function updateProceso($itemplan, $arrayData, $fechaActual) {
        $this->db->where('itemplan', $itemplan);
        $this->db->update('proceso_piloto', $arrayData);

        if($this->db->affected_rows() < 1) {
            $data = array('error' => EXIT_ERROR, 'msj' => 'error, no se registro');
        } else {
            $data = $this->inserLogProcesoPiloto($itemplan, $arrayData, $fechaActual);
        }
        return $data;
    }

    function insertAutoObra($arrayData) {
        $this->db->insert('planobra_x_auto', $arrayData);

        if($this->db->affected_rows() < 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se registro placa');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function updateEstadoItemplan($itemplan, $idEstadoPlan, $fecha) {
        $this->db->where('itemplan', $itemplan);
        $this->db->update('planobra', array('idEstadoPlan' => $idEstadoPlan));

        if($this->db->affected_rows() != 1) {
            $data = array('error' => EXIT_ERROR, 'msj' => 'error, no se actualiz&oacute; el estado');
        } else {
            $data = $this->inserLogItemplan($itemplan, $idEstadoPlan, $fecha);
        }
        return $data;
    }

    function inserLogItemplan($itemplan, $idEstadoPlan, $fecha) {
        $data = array(
                        "tabla"            => 'Proceso Piloto',
                        "actividad"        => 'ingresar',
                        "itemplan"         => $itemplan,
                        "itemplan_default" => 'idEstadoPlan:'.$idEstadoPlan,
                        "fecha_registro"   => $fecha,
                        "id_usuario"       => $this->session->userdata('idPersonaSession')
                     );
        $this->db->insert('log_planobra', $data);
        if ($this->db->affected_rows() < 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se registro');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function inserLogProcesoPiloto($itemplan, $arrayData, $fechaActual) {
        $arrayData['itemplan']          = $itemplan;
        $arrayData['idUsuarioRegistro'] = $this->session->userdata('idPersonaSession');
        $arrayData['fechaRegistro']     = $fechaActual;

        unset($arrayData['id_config']); //quito el idConfig del JSON ya que la tabla log no tiene ese campo
        unset($arrayData['fecha_reg_replanteo']);
        unset($arrayData['duracion_replanteo']);
        unset($arrayData['fecha_reg_elaboracion_fuit']);
        unset($arrayData['duracion_elaboracion_fuit']);
        unset($arrayData['duracion_entrega_fuit']);
        unset($arrayData['fecha_reg_entrega_fuit']);
        unset($arrayData['fecha_reg_instalacion_pex']);
        unset($arrayData['duracion_instalacion_pex']);

        $this->db->insert('log_proceso_piloto', $arrayData);

        if ($this->db->affected_rows() < 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se registro log');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function getCtoPlanObra($itemplan) {
        $sql = "SELECT tipo_sede
                  FROM planobra
                 WHERE itemplan = ? ";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['tipo_sede'];         
    }

    function getBitacoraMotivo($itemplan, $idEstado) {
        $sql = "SELECT pp.idMotivo, 
                       pp.idEstado, 
                       pp.comentario, 
                       pp.fechaRegistro, 
                       (SELECT nombre 
                          FROM usuario 
                         WHERE id_usuario = pp.idUsuarioRegistro) usuarioDesc, 
                       mo.motivoDesc
                  FROM proceso_piloto_x_motivo pp,
                       motivo mo
                 WHERE mo.idMotivo = pp.idMotivo
                   AND pp.idEstado = ?
                   AND pp.itemplan = ?";
        $result = $this->db->query($sql, array($idEstado, $itemplan));
        return $result->result_array();               
    }

    function insertPOPiloto($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO, $arrayLogPO) {
        $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
            $this->db->trans_begin();
            $this->db->insert_batch('planobra_po', $arrayPlanPO);
            if ($this->db->trans_status() === TRUE) {
                $this->db->insert_batch('detalleplan', $arrayDetallePlan);
                if ($this->db->trans_status() === TRUE) {
                    $this->db->insert_batch('planobra_po_detalle', $arrayDetallePO);
                    if ($this->db->trans_status() === TRUE) {
                        $this->db->insert_batch('log_planobra_po', $arrayLogPO);
                        if($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            $data['error']= EXIT_SUCCESS;
                        } else {
                            $this->db->trans_rollback();
                            throw new Exception('error al insertar log');
                        }
                    } else {
                        $this->db->trans_rollback();
                        throw new Exception('error al ingresar PO DETALLE');
                    }                
                } else{
                    $this->db->trans_rollback();
                    throw new Exception('error al ingresar DETALLE PLAN');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR al ingresar PLAN OBRA PO');
            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	        $this->db->trans_rollback();
	    }
	    return $data;
    }

    function getAgendamiento($fecha) {
        $ideecc  = $this->session->userdata("eeccSession");
        $sql = "SELECT a.idAgendamiento,
                       a.idCuotaAgenda,
                       a.fecha_registro,
                       a.itemplan,
                       DATE(a.fecha_agendamiento)fecha_agendamiento,
                       UNIX_TIMESTAMP(a.fecha_agendamiento)*1000 fechaMilisec,
                       CASE WHEN a.flg_tipo = 1 THEN 'AGENDA REPLANTEO'
                            WHEN a.flg_tipo = 2 THEN 'AGENDA INSTALACION' END AS tipo,
                       (SELECT nombre 
                          FROM usuario 
						 WHERE id_usuario = a.idUsuarioRegistro)usuarioRegistro,
					   (SELECT nombre 
                          FROM usuario 
						 WHERE id_usuario = a.idUsuarioConfirmacion)usuarioConfirmacion,
                       CASE WHEN a.flg_estado = ".FLG_CONFIRMADO." THEN 'CONFIRMADO'
                            WHEN a.flg_estado = ".FLG_CANCELADO." THEN 'CANCELADO'
                            ELSE 'SIN CONFIRMAR' END estado,
                        a.flg_estado    
                  FROM agendamiento a
                 WHERE DATE(fecha_agendamiento) = COALESCE(?, DATE(fecha_agendamiento))";
        $result = $this->db->query($sql, array($fecha));
        return $result->result_array();          
    }
}