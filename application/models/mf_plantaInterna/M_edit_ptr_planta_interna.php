<?php

class M_edit_ptr_planta_interna extends CI_Model
{

    function __construct()
    {
        parent::__construct();

    }

    function getZonalByItemplan($itemplan)
    {
        $Query = " SELECT idZonal, idSubProyecto FROM planobra where itemplan = ? AND flg_transporte IS NULL";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
        return $result;
    }

    function getAllActividadesByPTR($ptr){
        $Query = "SELECT * FROM ptr_x_actividades_x_zonal where ptr =?;";
        $result = $this->db->query($Query, array($ptr));
        return $result;
    }

    function getIDAllActividadesByPTR($ptr){
        $Query = "SELECT id_ptr_x_actividades_x_zonal FROM ptr_x_actividades_x_zonal where ptr =?;";
        $result = $this->db->query($Query, array($ptr));
        return $result;
    }

    function getAllActividadesEdit($idSubProyecto, $idZonal, $ptr)
    {
        $Query = " SELECT api.*,asp.*,az.*,paz.id_ptr_x_actividades_x_zonal 
                    FROM actividad_x_subproyecto asp, actividades_x_zonal az, partidas api
                    LEFT JOIN ptr_x_actividades_x_zonal paz ON paz.id_actividad = api.idActividad AND paz.ptr = '".$ptr."'
                    WHERE api.idActividad = asp.idActividad
                    AND  api.estado   = 1    
                    AND  api.flg_tipo = 1    
                    AND asp.idSubProyecto = '" .$idSubProyecto. "'
                    AND az.idZonal = '" .$idZonal. "'";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllActividadesTableTotal($idSubProyecto, $idZonal, $ptr)
    {
        $Query = " SELECT api.*,asp.*,az.*,paz.id_ptr_x_actividades_x_zonal,paz.cantidad,paz.costo_mat, paz.costo_mo, paz.costo_mat as costoMAT, paz.costo_mo as costoMO, paz.total
                    FROM actividad_x_subproyecto asp, actividades_x_zonal az, partidas api
                    LEFT JOIN ptr_x_actividades_x_zonal paz ON paz.id_actividad = api.idActividad AND paz.ptr = '".$ptr."'
                    WHERE api.idActividad = asp.idActividad
                    AND  api.estado = 1
                    AND  api.flg_tipo = 1    
                    AND asp.idSubProyecto = '" .$idSubProyecto. "'
                    AND az.idZonal = '" .$idZonal. "'
                    AND paz.ptr is not null";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function insertPTRPlantaInterna($estado, $vale_reserva,
                                    $usua_crea, $fecha_crea,
                                    $ultimo_estado, $fecha_ultimo_estado,
                                    $usua_ultimo_estado, $itemplan,
                                    $idSubProyectoEstacion, $actividades)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $dataTrans = array(
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
                        $datatrans['id_actividad_x_zonal'] = $ar['id_actividad_x_zonal'];
                        $datatrans['cantidad'] = $ar['cantidad'];
                        $datatrans['costo_mo'] = $ar['costoMO'];
                        $datatrans['costo_mat'] = $ar['costoMAT'];
                        $datatrans['total'] = $ar['total'];
                        $datatrans['precio'] = $ar['costo'];
                        $datatrans['baremo'] = $ar['baremo'];
                        $datatrans['descripcion'] = $ar['descripcion'];
                        $datatrans['itemplan'] = $itemplan;
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

    function editActividadesPTRPlantaInterna($arrayInsert, $arrayUpdate, $arayDelete){
        
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{

            $this->db->trans_begin();

            $this->db->insert_batch('ptr_x_actividades_x_zonal', $arrayInsert);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el itemplanestacionavance.');
            }else{
                $this->db->update_batch('ptr_x_actividades_x_zonal',$arrayUpdate, 'id_ptr_x_actividades_x_zonal');
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al actualizar el itemplanestacionavance.');
                }else{
                    if($arayDelete!=null){
                        $this->db->where_in('id_ptr_x_actividades_x_zonal', $arayDelete);
                        $this->db->delete('ptr_x_actividades_x_zonal');
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Hubo un error al actualizar el itemplanestacionavance.');
                        }else {
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualizo correctamente!';
                            $this->db->trans_commit();
                        }
                    }else {
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se actualizo correctamente!';
                        $this->db->trans_commit();
                    }
                }

            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function updateEstadoUltimo($itemp, $ptr, $arrayData) {
        $this->db->where('itemplan', $itemp);
        $this->db->where('ptr'     , $ptr);
        $this->db->update('ptr_planta_interna', $arrayData);
    }
}


