<?php

class M_plantaInterna extends CI_Model
{

    function __construct()
    {
        parent::__construct();

    }

    function getZonalByItemplan($itemplan)
    {
        $Query = " SELECT idZonal, idEmpresaColab AS idEmp, idSubProyecto FROM planobra where itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));

        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
        //return $result;
    }


    function getAllActividades($idZonal, $idSubProyecto)
    {
        $Query = " SELECT * FROM partidas api, actividad_x_subproyecto asp, actividades_x_zonal az
                    WHERE api.idActividad = asp.idActividad
                    AND  api.estado = 1
                    AND  api.flg_tipo = 1    
                    AND asp.idSubProyecto = '" . $idSubProyecto . "'
                    AND az.idZonal = '" . $idZonal . "'";
        $result = $this->db->query($Query, array());
        return $result;
    }
	
	function getAllActividadesPqt($idZonal, $idSubProyecto, $idEmpresaColab)
    {
        $Query = " SELECT * FROM partidas api, actividad_x_subproyecto asp, actividades_x_zonal_pqt az
                    WHERE api.idActividad = asp.idActividad
                    AND  api.estado = 1
                    AND  api.flg_tipo = 1    
                    AND asp.idSubProyecto = '" . $idSubProyecto . "'
					AND az.idEmpresaColab = '" . $idEmpresaColab . "'
                    AND az.idZonal = '" . $idZonal . "'";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function countPtrPlantaInterna($itemplan) {
        $sql = "SELECT COUNT(1) as count
                  FROM ptr_planta_interna 
                 WHERE itemplan = '".$itemplan."'
                 AND  rangoPtr != 6";
        $result = $this->db->query($sql);
        return $result->row()->count;         
    }
    
    function insertPTRPlantaInterna($estado, $vale_reserva,
                                    $usua_crea, $fecha_crea,
                                    $ultimo_estado, $fecha_ultimo_estado,
                                    $usua_ultimo_estado, $itemplan,
                                    $idSubProyectoEstacion, $actividades, $codigoPO)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $dataTrans = array(
			    "ptr"    => $codigoPO,
                "estado" => $estado,
                "vale_reserva" => $vale_reserva,
                "usua_crea" => $usua_crea,
                "fecha_crea" => $fecha_crea,
                "ultimo_estado" => $ultimo_estado,
                "fecha_ultimo_estado" => $fecha_ultimo_estado,
                "usua_ultimo_estado" => $usua_ultimo_estado,
                "itemplan" => $itemplan,
                "idSubProyectoEstacion" => $idSubProyectoEstacion,
                "rangoPtr" => '1'
            );
            $this->db->insert('ptr_planta_interna', $dataTrans);
			
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el Subproyecto - Pep');
            } else {
                $arrayInsert = array();
                foreach ($actividades as $ar) {
                    if ($ar != null) {
                        $datatrans['id_actividad']   = $ar['idActividad'];
                        $datatrans['cantidad']       = $ar['cantidad'];
                        $datatrans['cantidad_final'] = $ar['cantidad'];
                        $datatrans['costo_mo']       = $ar['costoMO'];
                        $datatrans['costo_mat']      = $ar['costoMAT'];
                        $datatrans['total']          = $ar['total'];
                        $datatrans['precio']         = $ar['costo'];
                        $datatrans['baremo']         = $ar['baremo'];
                        $datatrans['descripcion']    = $ar['descripcion'];
						$datatrans['ptr']    	     = $codigoPO;
                        $datatrans['itemplan']       = $itemplan;
                        array_push($arrayInsert, $datatrans);
                    }
                }
	
                $this->db->insert_batch('ptr_x_actividades_x_zonal', $arrayInsert);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al insertar el subproyectoestacion.');
                } else {
                    $this->db->trans_commit();
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se inserto correctamente!';
                }
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function validaItemplan($itemplan, $fecha, $idEstadoPlan) {
        $idUsuario = $this->session->userdata('idPersonaSession');
			
        $dataUpdate = array(
            "idEstadoPlan" => $idEstadoPlan,
            "fechaEjecucion" => $fecha,
			"usu_upd"        => $idUsuario,
			"fecha_upd"      => $fecha,
			"descripcion"   => 'VALIDACION PIN'
        );

        $this->db->where('itemplan', $itemplan);
        $this->db->update('planobra', $dataUpdate);
        
        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'Error al actualizar'; 
        }else{
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = 'Se agrego correctamente!';
        }
        return $data;
    }
    
    function hasMatPinValidationByItemplan($itemplan) {
        $sql = "SELECT COUNT(1) as count 
                FROM  planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea, area a
                WHERE po.idSubProyecto = sp.idSubProyecto
                AND sp.idSubProyecto = se.idSubProyecto
                AND se.idEstacionarea = ea.idEstacionArea
                AND ea.idArea = a.idArea
                AND po.itemplan = ?
                AND ea.idEstacion = 11
                AND tipoarea = 'MAT'";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row()->count;
    }
    
    function hasPoMatPinActivo($itemplan) {
        $sql = " SELECT COUNT(1) as count 
                FROM    planobra_po
                WHERE   itemplan = ?
                AND     idEstacion = 11
                AND     flg_tipo_area = 1 
                AND     estado_po not in (7,8);";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row()->count;
    }
    
	function getAllActividadesByContrato($itemplan)
    {
        $Query = " 	SELECT * 
					  FROM partida_x_contrato pxc,
						   planobra po,
						   partidas pa
					 WHERE pa.idActividad = pxc.id_partida
					   AND po.idContrato = pxc.id_contrato
					   AND po.itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }
}