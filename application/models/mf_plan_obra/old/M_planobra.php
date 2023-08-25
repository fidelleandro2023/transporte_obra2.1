<?php

class M_planobra extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function saveItemPlanEstadoCreado($itemPlan, $estadoPlan, $fechaActual, $idEstado) {
        $this->db->query("INSERT INTO ItemPlanEstadoCreado VALUES ('$itemPlan','$estadoPlan','$fechaActual','$idEstado');");
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getAllPlanesObra() {
        $Query = "SELECT planobra.itemplan as ItemPlan,
	    		  planobra.nombreProyecto as Nombre, 
       			(select subProyectoDesc from subproyecto where idSubProyecto=planobra.idSubProyecto) as Subproyecto, 
       			(select tipoCentralDesc from central where idCentral=planobra.idCentral)  as Central, 
       			(select zonalDesc from zonal where idZonal=planobra.idZonal) as Zonal,
       			(select empresaColabDesc from empresacolab where idEmpresaColab=planobra.idZonal) as EmpresaColab,  
   			    planobra.fechaInicio as fechaInicio, 
	   			planobra.fechaPrevEjec as fechaPreviaEjecucion, 
	   			(select estadoPlanDesc from estadoplan where idEstadoPlan=planobra.idEstadoPlan) as Estado 
	   			FROM planobra 
	   			where planobra.idEstadoPlan in (1)
	   			and planobra.itemplan in(SELECT itemplan from log_planobra 
	   									where tabla='planobra' and actividad='ingresar' and date(fecha_registro)=date(now()))
				ORDER BY planobra.itemplan desc;";

        $result = $this->db->query($Query, array());
        return $result;
    }

    function deleteLogImportPlanObraSub() {
        $Query = " DELETE FROM log_import_planobra_su where 1;";
        $result = $this->db->query($Query);
        return $result;
    }

    function insertarPlanobra($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, $itemMadre, $tipo_requerimiento, $tipo_diseno, $nombre_estudio, $duracion, $acceso_cliente, $tendido_externo, $tipo_sede, $tipo_cliente, $per, $costo_mat = null, $costo_mo = null, 
	                          $infoSubProyecto = null, $operador = null) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            // $this->db->trans_begin();

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
                "idCentralPqt" => (($idCentral == '') ? null : intval($idCentral)),
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
                "paquetizado_fg" => $infoSubProyecto['paquetizado_fg'],
                "costo_unitario_mat" => $costo_mat,
				"costo_unitario_mat_crea_oc" => $costo_mat,
                "costo_unitario_mo" => $costo_mo,
                "costo_unitario_mo_crea_oc" => $costo_mo,
                "cantFactorPlanificado" => 1,
				"usu_reg"   => 'SMART WEB',
				"fecha_reg" => $fechainicio,
				"operador"  => $operador
            );

            $this->db->insert('planobra', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                // $this->db->trans_rollback();
                throw new Exception('Error al insertar el plan de obra');
            } else {
                // $this->db->trans_commit();

                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            // $this->db->trans_rollback();
        }
        return $data;
    }

    /* OBTIENE DATOS DEL PLAN PARA LA EDICION */

    function getPlanObraInfo($idPlanobra) {
        $Query = " SELECT * FROM planobra where itemplan = ?";
        $result = $this->db->query($Query, array($idPlanobra));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function insertarLogPlanObra($itemplan, $idusuario, $tipoPlanta) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            //$fechainicio=date("Y-m-d H:i:s");

            $dataInsert = array(
                "tabla" => "planobra",
                "actividad" => "ingresar",
                "itemplan" => $itemplan,
                "fecha_registro" => $this->fechaActual(),
                "id_usuario" => $idusuario,
                "tipoPlanta" => $tipoPlanta
            );

            $this->db->insert('log_planobra', $dataInsert);

            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                //log_message('error', 'Error al insertar el log de plan de obra');
                throw new Exception('Error al insertar el plan de obra');
            } else {
                $this->db->trans_commit();

                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
            //log_message('error', 'Error al insertarLogPlanObra '. $e->getMessage());
        }
        return $data;
    }

    function obtenerUltimoRegistro() {
        $Query = " SELECT * from log_import_planobra_su order by itemplan desc limit 1;";
        $result = $this->db->query($Query, array());
        $itemplan = $result->row()->itemplan;
        return $itemplan;
    }

    function generarCodigoItemPlan($idProy, $idzonal) {
        try {

//concat(substring(year(NOW()),3,2),'-',
            $query = "SELECT concat(" . ANIO_CREATE_ITEMPLAN . ",'-',
						   (CASE WHEN LENGTH(" . $idProy . ") = 1 THEN CONCAT('0'," . $idProy . ") ELSE " . $idProy . " END),
							(SELECT case when (SELECT substring(zonalDesc,1,4) 
					  FROM zonal 
					 WHERE idzonal =" . $idzonal . ")='LIMA' THEN '1' else '2' end),
							CASE WHEN LENGTH(" . $idzonal . ") = 1 then CONCAT('0'," . $idzonal . ") ELSE " . $idzonal . " END) AS itemplan;";
            $result = $this->db->query($query, array());

            $itemplan = $result->row()->itemplan;

            return $itemplan;
        } catch (Exception $e) {
            return $e;
        }
    }

    /*     * METODO MODELO SISEGOPLANOBRA* */

    function saveSisegoPlanObra($itemplan, $from, $tipo_obra, $nap_nombre, $nap_num_troncal, $nap_cant_hilos_habi, $nap_nodo, $nap_coord_x, $nap_coord_y, $nap_ubicacion, $nap_num_pisos, $nap_zona, $fo_oscu_cant_hilos, $fo_oscu_cant_nodos, $trasla_re_cable_externo, $trasla_re_cable_interno, $fo_tra_cant_hilos, $fo_tra_cant_hilos_hab, $arrayNodos, $licenciaAfirm, $pisoGlobal, $sala, $nroODF, $bandeja, $nroHilo) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            $dataInsert = array(
                "itemplan" => $itemplan,
                "origen" => $from,
                "tipo_obra" => $tipo_obra,
                "nap_nombre" => $nap_nombre,
                "nap_num_troncal" => $nap_num_troncal,
                "nap_cant_hilos_habi" => $nap_cant_hilos_habi,
                "nap_nodo" => $nap_nodo,
                "nap_coord_x" => $nap_coord_x,
                "nap_coord_y" => $nap_coord_y,
                "nap_ubicacion" => $nap_ubicacion,
                "nap_num_pisos" => $nap_num_pisos,
                "nap_zona" => $nap_zona,
                "fo_oscu_cant_hilos" => $fo_oscu_cant_hilos,
                "fo_oscu_cant_nodos" => $fo_oscu_cant_nodos,
                "trasla_re_cable_externo" => $trasla_re_cable_externo,
                "trasla_re_cable_interno" => $trasla_re_cable_interno,
                "fo_tra_cant_hilos" => $fo_tra_cant_hilos,
                "fo_tra_cant_hilos_hab" => $fo_tra_cant_hilos_hab,
                "fec_registro" => $this->fechaActual(),
                "usuario_registro" => $this->session->userdata('idPersonaSession'),
                "licencia" => $licenciaAfirm,
                "piso_g" => $pisoGlobal,
                "sala" => $sala,
                "nro_odf" => $nroODF,
                "bandeja" => $bandeja,
                "nro_hilo" => $nroHilo
            );

            $this->db->insert('sisego_planobra', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el sisego_planobra');
            } else {
                $this->db->insert_batch('sisego_planobra_x_nodos_fo_oscu', $arrayNodos);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al insertar el sisego_planobra_x_nodos_fo_oscu.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getInfoItemplanSisegoPlanObra($itemplan) {
        $Query = "SELECT    sp.*,
							ti.*,
							COALESCE(estado_oc, 'SIN SOLICITUD OC') as estado_oc,
                            tt.pep1,
                            (SELECT CASE WHEN monto_temporal > 0 THEN 'CON PRESUPUESTO'
                                         ELSE 'SIN PRESUPUESTO' END
                               FROM sap_detalle s
							  WHERE s.pep1 = tt.pep1) estatus_pep,
							po.orden_compra
                    FROM    (sisego_planobra sp, 
                            tipo_obra ti,
                            planobra po) 
				LEFT JOIN  ( SELECT i.itemplan,
									s.pep1,
									 CASE WHEN tipo_solicitud = 1 AND estado = 1 THEN 'SOLICITUD OC CREADA'
										  WHEN tipo_solicitud = 1 AND estado = 2 THEN 'OC CREADA'
										  WHEN tipo_solicitud = 3 AND estado = 2 THEN 'OC CERTIFICADA'
										  WHEN tipo_solicitud = 4 AND estado = 2 THEN 'OC ANULADA' END estado_oc
								FROM solicitud_orden_compra s,
									 itemplan_x_solicitud_oc i
							   WHERE s.codigo_solicitud = i.codigo_solicitud_oc
								 AND s.estado <> 3
								 AND i.itemplan = ?
								ORDER BY tipo_solicitud DESC
							limit 1)tt ON tt.itemplan = sp.itemplan
                    WHERE   sp.tipo_obra    = ti.idtipo_obra 
                    AND     sp.itemplan     = ?
                    AND sp.itemplan = po.itemplan";

        $result = $this->db->query($Query, array($itemplan, $itemplan));
        return $result;
    }

    function getNodosByTipoObraItemplan($itemplan, $origen) {
        $Query = " SELECT  * 
                    FROM   sisego_planobra_x_nodos_fo_oscu
                	WHERE  itemplan    = ?
                	AND    origen      = ?";

        $result = $this->db->query($Query, array($itemplan, $origen));
        return $result;
    }

    function getComboTipoObra() {
        $sql = "SELECT idtipo_obra, descripcion 
				 FROM tipo_obra";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function saveFileCotizacionInit($itemplan, $pathFile) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataimg = array(
                "path_pdf_to_cotiza" => $pathFile
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra_cotizacion', $dataimg);
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

    function saveDetalleObraPublica($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->insert('planobra_detalle_obras_publicas', $dataInsert);
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

    function insertClusterFromSisego($padre, $hijosArray) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('planobra_cluster', $padre);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el planobra_cluster');
            } else {
                $this->db->insert_batch('planobra_cluster_hijos', $hijosArray);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al insertar el planobra_cluster_hijos.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function insertarPlanobraCluster($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, $fechaCreacion, $fecPrevistaEjec) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
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
                "fecha_creacion" => $fechaCreacion,
                "has_cotizacion" => $has_coti,
                "fechaPrevEjec" => $fecPrevistaEjec
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

    function getDatosClusterByCod($codigo) {
        $Query = "SELECT
                    	sp.idProyecto,
                    	c.idZonal,
                    	sp.idSubProyecto,
                    	c.idCentral,
                    	c.idEmpresaColab,
                    	pc.sisego,
                    	pc.coordX,
                    	pc.coordY,
	                   pc.tiempo_ejecu_planta_externa
            	FROM
                    	planobra_cluster pc,
                    	subproyecto sp,
                    	central c
            	WHERE
             	       pc.idSubProyecto = sp.idSubProyecto
            	AND    pc.idCentral = c.idCentral
            	AND    pc.codigo_cluster = ? LIMIT 1";
        $result = $this->db->query($Query, array($codigo));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function updateEstadoCluster($codigo, $datoArray) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->where('codigo_cluster', $codigo);
            $this->db->update('planobra_cluster', $datoArray);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar planobra_cluster.');
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

    function updateEstadoClusterByEstado($codigo, $datoArray, $estado) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->where('estado', $estado);
            $this->db->where('codigo_cluster', $codigo);
            $this->db->update('planobra_cluster', $datoArray);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar planobra_cluster.');
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

    function updateEstadoBySisegoCotizacion($sisego, $datoArray, $flg_tipo) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->where('sisego', $sisego);
            $this->db->where('flg_tipo', $flg_tipo);
            $this->db->update('planobra_cluster', $datoArray);
			
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    public function changeEstadoEnObraWithLog($itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataUpdate = array(
                'idEstadoPlan' => ID_ESTADO_PLAN_EN_OBRA
            );
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);

            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {

                $dataUpdateLog = array(
                    'tabla' => 'planobra',
                    'actividad' => 'update',
                    'itemplan' => $itemplan,
                    'itemplan_default' => 'idEstadoPlan=' . ID_ESTADO_PLAN_EN_OBRA . '| AUTOMATICO CONFIGURACION SUBPROYECTO',
                    'fecha_registro' => $this->fechaActual(),
                    'id_usuario' => $this->session->userdata('idPersonaSession'),
                );

                $this->db->insert('log_planobra', $dataUpdateLog);

                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
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

    function insertarPlanobraPaquetizado($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, $itemMadre, $tipo_requerimiento, $tipo_diseno, $nombre_estudio, $duracion, $acceso_cliente, $tendido_externo, $tipo_sede, $tipo_cliente, $per, $idTipoFactorMedicion, $cantFactorMedicion, $paquetizado_fg, $idUsuario, $costoMo = null) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            $fechainicio = date("Y-m-d H:i:s");

            $idProvincia = 1;
            $idDepartamento = 1;
            $hasAdelanto = '0';

            //log_message('error', 'vamos a insertar '.$itemplan);

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
                "idCentral" => (($idCentral == '') ? null : intval($idCentral)), /* temporal hasta que se actualice los querys* */
                "idCentralPqt" => (($idCentral == '') ? null : intval($idCentral)),
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
                "idPqtTipoFactorMedicion" => $idTipoFactorMedicion,
                "cantFactorPlanificado" => $cantFactorMedicion,
                "paquetizado_fg" => $paquetizado_fg,
                "usu_reg" => $idUsuario,
                "fecha_reg" => $this->fechaActual(),
                "costo_unitario_mo" => $costoMo,
                "costo_unitario_mo_crea_oc" => $costoMo
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

    #automatico en aprobacion por configuracion, EL 13.02.2020 SE adiciono que pase a en obra y no se quede ne aprobacion pedido owen saravia

    public function changeEstadoEnAprobacion($itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            $dataUpdate = array(
                'idEstadoPlan' => ID_ESTADO_DISENIO,
                'usu_upd' => 'SIN ADJUDICACION',
                'fecha_upd' => $this->fechaActual(),
                'descripcion' => 'CONFIG. SUBPROYECTO'
            );
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {

                $dataUpdate = array(
                    'idEstadoPlan' => ID_ESTADO_EN_LICENCIA,
                    'usu_upd' => 'SIN DISEÑO',
                    'fecha_upd' => $this->fechaActual(),
                    'descripcion' => 'CONFIG. SUBPROYECTO'
                );
                $this->db->where('itemPlan', $itemplan);
                $this->db->update('planobra', $dataUpdate);
                if ($this->db->trans_status() === false) {
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
                } else {
                    $dataUpdate = array(
                        'idEstadoPlan' => ID_ESTADO_EN_APROBACION,
                        'usu_upd' => 'SIN LICENCIA',
                        'fecha_upd' => $this->fechaActual(),
                        'descripcion' => 'CONFIG. SUBPROYECTO'
                    );
                    $this->db->where('itemPlan', $itemplan);
                    $this->db->update('planobra', $dataUpdate);
                    if ($this->db->trans_status() === false) {
                        throw new Exception('Hubo un error al actualizar el estadoplan.');
                    } else {
                        $dataUpdate = array(
                            'idEstadoPlan' => ID_ESTADO_PLAN_EN_OBRA,
                            'usu_upd' => 'SIN LICENCIA',
                            'fecha_upd' => $this->fechaActual(),
                            'descripcion' => 'CONFIG. SUBPROYECTO'
                        );
                        $this->db->where('itemPlan', $itemplan);
                        $this->db->update('planobra', $dataUpdate);
                        if ($this->db->trans_status() === false) {
                            throw new Exception('Hubo un error al actualizar el estadoplan.');
                        } else {
                            $dataUpdateLog = array(
                                'tabla' => 'planobra',
                                'actividad' => 'update',
                                'itemplan' => $itemplan,
                                'itemplan_default' => 'idEstadoPlan=' . ID_ESTADO_PLAN_EN_OBRA . '| AUTOMATICO CONFIGURACION SUBPROYECTO',
                                'fecha_registro' => $this->fechaActual(),
                                'id_usuario' => $this->session->userdata('idPersonaSession'),
                            );

                            $this->db->insert('log_planobra', $dataUpdateLog);

                            if ($this->db->affected_rows() != 1) {
                                $this->db->trans_rollback();
                                throw new Exception('Hubo un error al actualizar el estadoplan.');
                            } else {
                                $data['error'] = EXIT_SUCCESS;
                                $data['msj'] = 'Se actualizo correctamente!';
                                $this->db->trans_commit();
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function insertSisegoOpex($dataArray) {
        $this->db->insert('sisego_opex', $dataArray);

        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Error al ingresar el OPEX!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se ingreso correctamente!';
        }

        return $data;
    }

    /////// -------- IVAN --------///

 function idCuentaOpexSisego($ceco, $cuenta, $areFuncional) {
        $Query = "SELECT * FROM sisego_opex WHERE ceco='$ceco' AND cuenta='$cuenta' AND area_funcional='$areFuncional';";
        $result = $this->db->query($Query);
        if ($result->row() != null) {
            return $result->row()->id;
        } else {
            return null;
        }
    }
    
    function sumaOpexSisego($idOpexSisego, $monto) {
        $Query = "UPDATE sisego_opex SET monto = (monto + $monto) WHERE id='$idOpexSisego';";
        $this->db->query($Query);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

public function funcionOCopex($itemfaultData, $idOpex, $idUsuario) {
        $Query = "SELECT createOCToItemplanOpexFromSisego('$itemfaultData','$idOpex','$idUsuario') as respuesta;";
        $result = $this->db->query($Query);
        if ($result->row() != null) {
            return $result->row()->respuesta;
        } else {
            return null;
        }
    }

    function saveConfigOpex($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('cuenta_opex_pex', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la configuracion Opex');
            } else {
                $data['idOpex'] = $this->db->insert_id();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro la configuracion Opex';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
       function saveEventoOpex($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('subproyectoopexitemplan', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la configuracion Opex');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro correctamente la configuracion Opex!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function funcionOCopexSisego($itemfaultData, $ceco, $cuenta, $area_funcional, $idUsuario) {
        $Query = "SELECT createOCToItemplanOpexSisego('$itemfaultData','$ceco','$cuenta','$area_funcional','$idUsuario') as respuesta;";
        $result = $this->db->query($Query);
        if ($result->row() != null) {
            return $result->row()->respuesta;
        } else {
            return null;
        }
    }

    function idCuentaOpex($ceco, $cuenta, $areFuncional) {
        $Query = "SELECT * FROM cuenta_opex_pex WHERE ceco='$ceco' AND cuenta='$cuenta' AND areaFuncional='$areFuncional';";
        $result = $this->db->query($Query);
        if ($result->row() != null) {
            return $result->row()->idOpex;
        } else {
            return null;
        }
    }

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
	
	function getInfoOc($itemplan) {
        $Query = "SELECT i.itemplan,
						 s.pep1,
						 CASE WHEN tipo_solicitud = 1 AND estado = 1 THEN 'SOLICITUD OC CREADA'
							  WHEN tipo_solicitud = 1 AND estado = 2 THEN 'OC CREADA'
							  WHEN tipo_solicitud = 3 AND estado = 2 THEN 'OC CERTIFICADA'
							  WHEN tipo_solicitud = 4 AND estado = 2 THEN 'OC ANULADA' END estado_oc,
					     s.orden_compra,
						 (SELECT FORMAT(ROUND(monto_temporal, 2), 2)
                            FROM sap_detalle ss
						   WHERE ss.pep1 = s.pep1) estatus_pep
					FROM solicitud_orden_compra s,
						 itemplan_x_solicitud_oc i
				   WHERE s.codigo_solicitud = i.codigo_solicitud_oc
					 AND s.estado <> 3
					 AND i.itemplan = ?
					ORDER BY tipo_solicitud DESC
				limit 1";

        $result = $this->db->query($Query, array($itemplan));
        return $result->row_array();
    }
}
