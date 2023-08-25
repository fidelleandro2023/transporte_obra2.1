<?php

class M_utils extends CI_Model
{

    private $bd_sam;

    function __construct()
    {
        parent::__construct();
    }

    //    public function MasivoGetItemPlam() {
    //        $sql = "SELECT po.itemplan,
    //                 po.idSubProyecto
    //                 FROM planobra po LEFT JOIN pre_diseno pre ON (po.itemplan = pre.itemplan)
    //                 WHERE po.idEstadoPlan = 2 AND po.orden_compra IS NOT NULL
    //                 AND pre.itemplan IS NULL";
    //        $result = $this->db->query($sql);
    //        return $result->result();
    //    }

    public function MasivoGetItemPlam()
    {
        $sql = "SELECT
	po.itemplan,
	po.idSubProyecto
	FROM
	planobra po
	LEFT JOIN subproyecto sub on sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN pre_diseno pre ON ( po.itemplan = pre.itemplan ) 
	WHERE
	po.idEstadoPlan = 2 
	AND pre.itemplan IS NULL
	AND sub.idTipoPlanta = 1;";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function CountPredisenoByItemplan($itemplan)
    {
        $sql = "SELECT * FROM pre_diseno WHERE itemplan ='$itemplan'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getAllEECC()
    {
        $Query = "  SELECT * 
                     FROM empresacolab
                    WHERE idEmpresacolab NOT IN (5,6,9)
                    ORDER BY empresaColabDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllEECCINCLTDP()
    {
        $Query = "  SELECT *
                     FROM empresacolab
                    WHERE idEmpresacolab  NOT IN (5,9)
                    ORDER BY empresaColabDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllZonal()
    {
        $Query = "  SELECT * 
	                FROM zonal
	                ORDER BY zonalDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllZonalGroup()
    {
        $Query = "SELECT SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zonalDesc,
	                     idZonal 
	                FROM zonal GROUP BY SUBSTRING_INDEX( zonalDesc , ' ', 1 ) ORDER BY zonalDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllZonalIndex()
    {
        $Query = "SELECT idZonal, SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zona FROM zonal GROUP BY (zona)";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllSubProyecto($idTipoPlantaInterna = null)
    { //SE QUITO 2017 POR PEDIDO DE OWEN EN PRESUPUESTO 07.02.2019
        $Query = "SELECT * 
	                FROM subproyecto
				   WHERE idTipoPlanta = COALESCE(?, idTipoPlanta) 
				     AND SUBSTRING_INDEX( subproyectoDesc , ' ', 1 ) NOT IN(2016)
	             ORDER BY subProyectoDesc";
        $result = $this->db->query($Query, array($idTipoPlantaInterna));
        return $result;
    }

    function getAllSubProyectoByProyecto($idProyecto, $flgRegitroCableadoEdif = null)
    {
        $Query = "  SELECT *
	                FROM subproyecto
	                WHERE idProyecto = ?
					  AND CASE WHEN ? = 1 AND idProyecto = 21 THEN idSubProyecto NOT IN (96,97,98,99)
					           ELSE TRUE END
	                  AND SUBSTRING_INDEX( subproyectoDesc , ' ', 1 ) NOT IN(2016,2017)
					  AND estado = 1
	                ORDER BY subProyectoDesc";
        $result = $this->db->query($Query, array($idProyecto, $flgRegitroCableadoEdif));
        return $result;
    }

    function getAllProyecto()
    {
        $Query = "  SELECT *
	                FROM proyecto
	                ORDER BY proyectoDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllPerfiles()
    {
        $Query = "  SELECT *
	                FROM perfil
	                ORDER BY desc_perfil";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllAreas()
    {
        $Query = " SELECT * FROM area ORDER BY tipoArea, areaDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllPep1()
    {
        $Query = " SELECT distinct(pep1) FROM pep1_pep2 order by pep1 desc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    // FILTROS DE CONSULTA

    function getAllItemplan()
    {
        $Query = " SELECT * FROM planobra ORDER BY itemPlan";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllNombreDeProyectos()
    {
        $Query = "  SELECT DISTINCT (nombreProyecto) 
					FROM planobra 
					ORDER BY nombreProyecto ";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllNodos()
    {
        $Query = "  SELECT idCentral,codigo,tipoCentralDesc 
					FROM central 
					ORDER BY codigo ";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getEstadosItemplan()
    {
        $Query = "  SELECT idEstadoPlan, UPPER(estadoPlanDesc) AS  estadoPlanDesc
					FROM estadoplan 
					WHERE idEstadoPlan NOT IN (1,2) 
					ORDER BY idEstadoPlan";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllRegion()
    {
        $Query = "  select distinct region from central ORDER by region ";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllSubProyectoNoFaseByProyecto($idProyecto)
    {
        $Query = "  SELECT * FROM subproyecto where substring(subProyectoDesc,1,4) != '2017' and substring(subProyectoDesc,1,4) != '2018'
	                and idProyecto = ?
	                ORDER BY subProyectoDesc";
        $result = $this->db->query($Query, array($idProyecto));
        return $result;
    }

    function getAllTipoCentral()
    {
        $Query = "  SELECT * FROM tipocentral;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllEstadosPlan($itemplan)
    {
        $Query = 'SELECT (CASE WHEN idEstadoPlan IN (8,1,2,7) THEN 6 WHEN idEstadoPlan IN (3) THEN 10 WHEN idEstadoPlan IN (10) THEN 3 else 0 END ) as idEstadoPlan, (CASE WHEN idEstadoPlan IN (8,1,2,7) THEN "Cancelado" WHEN idEstadoPlan IN (3) THEN "Trunco" WHEN idEstadoPlan IN (10) THEN "En Obra" else 0 END ) as estadoPlanDesc FROM planobra WHERE itemplan = ?';
        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }

    function getAllEstacionNoDiseno()
    {
        $Query = " SELECT * FROM estacion WHERE idEstacion != 1;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllIdEstacionAreaByArea()
    {
        $Query = "  SELECT a.idArea, a.areaDesc, ea.idEstacionArea
                	FROM   area a, estacionarea ea
                	WHERE  a.idArea = ea.idArea
                	ORDER BY tipoArea, areaDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllTipoPlantal()
    {
        $Query = "  SELECT * FROM tipoplanta_transporte";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllPqtTipoFactorMedicion()
    {
        $Query = "  SELECT * FROM pqt_tipo_factor_medicion_transporte where idPqtTipoFactorMedicion <> 99";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllSubProyectoDesc()
    {
        $Query = "  SELECT     subproyecto.idSubProyecto, 
	                           subproyecto.tiempo,
	                           proyecto.proyectoDesc, 
	                           subproyecto.subProyectoDesc, 
	                           tipoplanta.tipoPlantadesc  
	                   FROM    subproyecto, proyecto, tipoplanta
            	       WHERE   subproyecto.idProyecto = proyecto.idProyecto
            	       AND     subproyecto.idTipoPlanta = tipoplanta.idTipoPlanta
            	    ORDER BY   proyectoDesc, subProyectoDesc;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllProyectoDesc()
    {
        $Query = "  SELECT 	proyecto.idProyecto,
                        	proyecto.proyectoDesc,
                        	tipocentral.tipoCentralDesc,
                        	tipolabel.tipoLabelDesc,
                            gerencia.gerenciaDesc
                	FROM 	( proyecto, tipocentral, tipolabel )
                    LEFT JOIN gerencia ON proyecto.idGerencia = gerencia.idGerencia
                	WHERE	proyecto.idTipoCentral = tipocentral.idTipoCentral
                	AND 	proyecto.idTipoLabel = tipolabel.idTipoLabel
                	ORDER BY proyecto.proyectoDesc;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllTipoLabel()
    {
        $Query = "  SELECT * FROM tipolabel;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getEstadoPlanByItemplan($itemplan)
    {
        $Query = "SELECT idEstadoPlan from planobra where itemplan = ?;";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['idEstadoPlan'];
        } else {
            return null;
        }
    }

    function getEstadoPlanByItemplanNombre($itemplan)
    {
        $Query = "SELECT p.idEstadoPlan, e.estadoPlanDesc from planobra p join estadoplan e on e.idEstadoPlan=p.idEstadoPlan where p.itemplan = ?;";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function updateEstadoPlanObra($itemplan, $estadoPlan)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession');

            $this->db->trans_begin();
            $dataUpdate = array(
                "idEstadoPlan" => $estadoPlan,
                "usu_upd" => $idUsuario,
                "fecha_upd" => $this->fechaActual()
            );

            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);

            #$this->db->where('itemPlan', $itemplan);
            #$this->db->update('pre_diseno', array("estado" => 3));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el updateEstadoPlanObra');
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

    function getEstaciondescByIdEstacion($idEstacion)
    {
        $Query = "SELECT estacionDesc from estacion where idEstacion = ?;";
        $result = $this->db->query($Query, array($idEstacion));
        if ($result->row() != null) {
            return $result->row_array()['estacionDesc'];
        } else {
            return null;
        }
    }

    function getMaxFechaFileDetallePlan()
    {
        $Query = "SELECT MAX(fecha_registro) as fecha_registro FROM log_planobra where tabla = 'detalle_plan_file'";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->row_array()['fecha_registro'];
        } else {
            return null;
        }
    }

    /*     * ********miguel rios 01052018************* */

    function getAllFase()
    {
        $Query = "  SELECT * FROM fase WHERE idFase != 1 ORDER BY faseDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllFaseSubproyecto($idSubProyecto)
    {
        $Query = " select x.fase, x.cantItemPlan,
						  (SELECT idFase FROM fase WHERE faseDesc = x.fase) AS idFase,
						  (select count(*) from planobra p INNER JOIN fase f on p.idfase = f.idfase where p.idSubProyecto = x.idSubProyecto and f.faseDesc = x.fase  AND p.idEstadoPlan NOT IN (6)) registrado
                    from subproyecto_fases_cant_itemplan x where x.idSubProyecto = ?
					and x.fase = 2023 ";#A PEDIDO DE OWEN SARAVIA 23-01-2023
        $result = $this->db->query($Query, array($idSubProyecto));
        //log_message('error', '$idSubProyecto ' . $idSubProyecto . ' $Query ' . $Query );

        return $result;
    }

    function getAllCentral()
    {
        $Query = "  SELECT idCentral ,CONCAT(codigo,'-',tipoCentralDesc) as tipoCentralDesc  FROM central;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllCentralPqt($idEmpresaColab = NULL)
    {
        $Query = "  SELECT idCentral ,
		                   CONCAT(codigo,'-',tipoCentralDesc) as tipoCentralDesc  
		              FROM pqt_central
					 WHERE idTipoCentral IN (1,2)
					   AND CASE WHEN ? IN (0,6) THEN true 
					            ELSE idEmpresaColab = COALESCE(?, idEmpresaColab) END;";
        $result = $this->db->query($Query, array($idEmpresaColab, $idEmpresaColab));
        return $result;
    }

    function getOnlyMDF()
    {
        $Query = " SELECT idCentral ,concat(codigo,'-',tipoCentralDesc) as tipoCentralDesc  FROM central
                    WHERE idTipoCentral = 1";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllEELEC()
    {
        $Query = "  SELECT * FROM empresaelec;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getZonalXCentral($idCentral)
    {
        $Query = "  SELECT zonal.idzonal,zonal.zonalDesc 
	    			from central left join zonal on central.idzonal=zonal.idzonal
					where central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        return $result;
    }

    function getZonalXCentralPqt($idCentral)
    {
        $Query = "SELECT zonal.idzonal,zonal.zonalDesc 
	    			from pqt_central left join zonal on pqt_central.idzonal=zonal.idzonal
					where pqt_central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        return $result;
    }

    function getJefaturaXCentral($idCentral)
    {
        $Query = "  SELECT central.jefatura
	    			from central 
					where central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        return $result;
    }

    function getEECCXCentral($idCentral, $flgRow = null)
    {
        $Query = "  SELECT empresacolab.idEmpresaColab,
		                   empresacolab.empresaColabDesc  
		              from central 
					left join empresacolab on central.idEmpresaColab=empresacolab.idEmpresaColab
					where central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        if ($flgRow == null) {
            return $result;
        } else {
            return $result->row_array();
        }
    }

    function getEECCXCentralPqt($idCentral, $flgRow = null)
    {
        $Query = "  SELECT empresacolab.idEmpresaColab,
		                   empresacolab.empresaColabDesc  
		              from pqt_central 
					left join empresacolab on pqt_central.idEmpresaColab=empresacolab.idEmpresaColab
					where pqt_central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        if ($flgRow == null) {
            return $result;
        } else {
            return $result->row_array();
        }
    }

    function getAllPerfil()
    {

        $Query = "  SELECT * from perfil;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllPermisos()
    {

        $Query = "  SELECT id_permiso, descripcion from permisos where id_padre is not null;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    /*     * ******************************************************************************************** */

    function getAllEmpresaColabDiseno()
    {
        $Query = "SELECT * FROM empresacolab_diseno";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getInfoItemplan($itemplan)
    {
        $Query = "SELECT * from planobra where itemplan = ?;";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    /*     * ***************************Miguel rios 09052018********************* */

    function getCalculoTiempoSubproyecto($fechaInicio, $subproy)
    {
        $Query = "  SELECT DATE_ADD('" . $fechaInicio . "', interval 
				(SELECT trim(substring(tiempo,1,2)) as tiempo from subproyecto where idSubProyecto=" . $subproy . ") day) AS fechaCalculo;";
        $result = $this->db->query($Query, array());
        $fechaCalculo = $result->row()->fechaCalculo;

        return $fechaCalculo;
    }

    /*     * ********************************************************************* */

    function getEstacionCmb($flg)
    {
        $query = "SELECT idEstacion, 
				     estacionDesc 
				FROM estacion
			   WHERE CASE WHEN ? = 1 THEN idEstacion IN (2,5) 
						  ELSE idEstacion = idEstacion END";
        $result = $this->db->query($query, array($flg));
        return $result->result();
    }

    function getPlantaCmb()
    {
        $query = "SELECT idTipoPlanta,
	                 tipoPlantaDesc 
	            FROM tipoplanta";
        $result = $this->db->query($query);
        return $result->result();
    }

    function validaPtrExiste($itemPlan, $idEstacion)
    {
        $query = " SELECT COUNT(1) as count
		   FROM pre_diseno, subproyectoestacion, estacionarea, estacion, detalleplan
		  WHERE pre_diseno.itemPlan = detalleplan.itemPlan
		    and detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
		    AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
		    AND estacionarea.idEstacion = estacion.idEstacion
		    AND pre_diseno.idEstacion = estacion.idEstacion
		    and pre_diseno.itemPlan   = ?
		    and estacion.idEstacion   = ? ";
        $result = $this->db->query($query, array($itemPlan, $idEstacion));
        return $result->row()->count;
    }

    function getJefaturaCmb()
    {
        $query = "SELECT jefatura 
			  FROM central
			GROUP BY jefatura";
        $result = $this->db->query($query);
        return $result->result();
    }

    function getJefaturaSapCmb()
    {
        $query = "SELECT idJefatura,
	               descripcion 
			  FROM jefatura_sap
			GROUP BY idJefatura
			ORDER BY descripcion";
        $result = $this->db->query($query);
        return $result->result();
    }

    function getProyectoCmb()
    {
        $query = "SELECT idProyecto, 
				   proyectoDesc,
				   idTipoCentral 
			  FROM proyecto";
        $result = $this->db->query($query);
        return $result->result();
    }

    function getSubProyectoCmb($idProyecto, $idTipoPlanta)
    {
        $query = "  SELECT idSubProyecto,
					 subProyectoDesc,
					 idProyecto,
					 idTipoPlanta
				FROM subproyecto
				WHERE idTipoPlanta = COALESCE(?, idTipoPlanta)
				  AND idProyecto   = COALESCE(?, idProyecto)
				  AND estado       = 1
			 ORDER BY subProyectoDesc";
        $result = $this->db->query($query, array($idTipoPlanta, $idProyecto));
        return $result->result();
    }

    function getAllSubProyectosCV()
    {
        $Query = ' SELECT * FROM subproyecto where idProyecto = ' . ID_PROYECTO_CRECIMIENTO_VERTICAL;
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getSerieCmb()
    {
        $query = "SELECT idSerieTroba, 
	                 serie 
		    FROM serie_troba
                   WHERE flgUtilizado = 0";
        $result = $this->db->query($query);
        return $result->result();
    }

    function getALLProyectosCV()
    {
        $Query = 'SELECT 	pdcv.itemplan, 
                    		pdcv.coordenada_x, 
                    		pdcv.coordenada_y, 
                    		pdcv.direccion, 
                    		pdcv.nombre_proyecto, 
                    		pdcv.numero,                            
                    		sp.subProyectoDesc, 
                            pdcv.avance,
                            e.empresaColabDesc,
                            pdcv.fec_termino_constru,
                            ep.estadoPlanDesc,
                            po.idEstadoPlan
                    FROM 	planobra po,
							planobra_detalle_cv pdcv,
                    		subproyecto sp,
                            central c,
                            empresacolab e,
                            estadoplan ep
                    WHERE 	pdcv.idSubProyecto   = sp.idSubProyecto
					AND		po.itemplan          = pdcv.itemplan
                    AND		po.idEstadoPlan		 = ep.idEstadoPlan
					AND		po.idCentral	     = c.idCentral
                    AND		c.idEmpresaColabCV   = e.idEmpresaColab;';
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getCoordenadasByEstaciones($itemPlan, $idEstacion)
    {
        $query = "SELECT * 
		            FROM(SELECT itemPlan,
								9 as idEstacion,
								coordX, 
								coordY 
						   FROM planobra
						  WHERE coordX <> ''		
							AND coordY <> '' 
						UNION ALL      
						 SELECT itemPlan, 
								idEstacion, 
								coordX, 
								coordY 
						   FROM pre_diseno
						   WHERE coordX <> ''
    						 AND coordY <> '')t
						  WHERE t.itemPlan = '" . $itemPlan . "'
							AND t.idEstacion = " . $idEstacion;
        $result = $this->db->query($query);
        return $result->row_array();
    }

    function getOpcFTecnicaAuditor()
    {
        $Query = "SELECT * FROM ficha_tecnica_opc_auditor";
        $result = $this->db->query($Query, array());
        return $result;
    }

    /*     * MIGUEL RIOS 12062018* */

    function getAllPep2()
    {
        $Query = " SELECT distinct pep2 from pep2_grafo where estado!=2 order by pep2";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllGrafoxPep2($pep2)
    {
        $Query = " SELECT grafo from pep2_grafo where estado!=2 and pep2 = ? ;";
        $result = $this->db->query($Query, array($pep2));
        return $result;
    }

    /*     * */


    /*     * ********MIGUEL RIOS 13062018********* */

    function getAllProyectoExcepcion()
    {

        $Query = "  SELECT p.*
	                FROM proyecto p, subproyecto s
                    WHERE p.idProyecto = s.idProyecto
                      AND s.paquetizado_fg IN (1,2)
					  AND s.idTipoPlanta in (1)
	                  AND p.idProyecto not in (21)
                      GROUP BY p.idProyecto
	                ORDER BY proyectoDesc";
        /* $Query = "  SELECT *
          FROM proyecto
          WHERE NOT idProyecto in (21)
          ORDER BY proyectoDesc" ; */

        $result = $this->db->query($Query, array());
        return $result;
    }

    function getPorcentajeCmb()
    {
        $sql = "SELECT t.porcentaje 
				  FROM(
					 SELECT 0 as porcentaje
					  UNION 
					 SELECT 25 
					  UNION 
					 SELECT 50
					  UNION
					 SELECT 75
					  UNION
					 SELECT 80
					  UNION 
					 SELECT 85
					  UNION 
					 SELECT 90
					  UNION    
					 SELECT 95
					  UNION 
					 SELECT 100
					)t";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getIdCentralByCentralDesc($codigo)
    {
        $Query = "SELECT idCentral, 
                          idZonal, 
                          idEmpresaColab,
                          tipoCentralDesc,
                          latitud,
                          longitud,
                          codigo, 
						  jefatura,
						  idEmpresaColabCV
                     FROM pqt_central 
                    WHERE UPPER(codigo) = UPPER(?)
                    LIMIT 1";
        $result = $this->db->query($Query, array($codigo));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getIdSubProyectoBySubProyectoDesc($descripcion)
    {
        $Query = "SELECT idSubProyecto from subproyecto where subProyectoDesc = ?;";
        $result = $this->db->query($Query, array($descripcion));

        if ($result->row() != null) {
            return $result->row_array()['idSubProyecto'];
        } else {
            return null;
        }
    }

    function getInfoItemplanLiquidacionSisegos($ptr, $itemplan)
    {
        $Query = "SELECT   po.idEstadoPlan, po.indicador, sp.idProyecto, dp.itemPlan, dp.poCod, 
                    		e.idEstacion, a.tipoArea, wu.jefatura_ptr, wu.eecc, e.estacionDesc,
                            CASE 	WHEN wu.eecc = 'COBRA' THEN 1 
									WHEN wu.eecc = 'LARI' THEN 2
									WHEN wu.eecc = 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.' THEN 3
									WHEN wu.eecc = 'CALATEL' THEN 4  END as idEecc, po.idCentral, sp.idSubProyecto,
                                    c.region, c.tipoCentralDesc, c.codigo, po.paquetizado_fg
                    FROM 	planobra po,
                    		subproyecto sp,
                    		detalleplan dp,
                    		web_unificada wu,
                    		subproyectoestacion se,
                    		estacionarea ea,
                    		estacion e,
                    		area a,
                    		central c
                    WHERE 	po.itemplan      = dp.itemplan
                    AND		po.idSubProyecto = sp.idSubProyecto
                    AND     po.idCentral     = c.idCentral
                    AND 	dp.poCod 		 = wu.ptr
                    AND 	dp.idSubProyectoEstacion 	= se.idSubProyectoEstacion
                    AND 	se.idEstacionArea 			= ea.idEstacionArea
                    AND 	ea.idEstacion	 = e.idEstacion
                    AND 	ea.idArea 		 = a.idArea
					AND		po.paquetizado_fg IS NULL
                    AND		dp.poCod 		 =  ?
                    AND 	dp.itemplan 	 =  ?
					UNION ALL
					SELECT   po.idEstadoPlan, po.indicador, sp.idProyecto, dp.itemPlan, dp.poCod, 
                    		e.idEstacion, a.tipoArea, wu.jefatura_ptr, wu.eecc, e.estacionDesc,
                            CASE 	WHEN wu.eecc = 'COBRA' THEN 1 
									WHEN wu.eecc = 'LARI' THEN 2
									WHEN wu.eecc = 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.' THEN 3
									WHEN wu.eecc = 'CALATEL' THEN 4  END as idEecc, po.idCentral, sp.idSubProyecto,
                                    c.region, c.tipoCentralDesc, c.codigo, po.paquetizado_fg
                    FROM 	planobra po,
                    		subproyecto sp,
                    		detalleplan dp,
                    		web_unificada wu,
                    		subproyectoestacion se,
                    		estacionarea ea,
                    		estacion e,
                    		area a,
                    		pqt_central c
                    WHERE 	po.itemplan      = dp.itemplan
                    AND		po.idSubProyecto = sp.idSubProyecto
                    AND     po.idCentralPqt  = c.idCentral
                    AND 	dp.poCod 		 = wu.ptr
                    AND 	dp.idSubProyectoEstacion 	= se.idSubProyectoEstacion
                    AND 	se.idEstacionArea 			= ea.idEstacionArea
                    AND 	ea.idEstacion	 = e.idEstacion
                    AND 	ea.idArea 		 = a.idArea
					AND		po.paquetizado_fg IN (1,2)
                    AND		dp.poCod 		 =  ?
                    AND 	dp.itemplan 	 =  ?";
        $result = $this->db->query($Query, array($ptr, $itemplan, $ptr, $itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getCodigoCentral($jefatura)
    {
        $sql = "SELECT distinct codigo 
    			  FROM central
    			 WHERE jefatura = '" . $jefatura . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function saveLogSigoplus($origen, $ptr, $itemplan, $vr, $sisego, $eecc, $jefatura, $motivo_error, $descripcion, $estado, $flgTipo = null, $data_json = null, $json_envia_web_po = null)
    {
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj'] = null;
        try {
            //$this->db->trans_begin();
            $data_json = ($data_json == null) ? null : json_encode($data_json);

            $data = array(
                "origen" => $origen,
                "ptr" => $ptr,
                "itemplan" => $itemplan,
                "vr" => $vr,
                "sisego" => $sisego,
                "fecha_registro" => $this->fechaActual(),
                "eecc" => $eecc,
                "jefatura" => $jefatura,
                "motivo_error" => $motivo_error,
                "descripcion" => $descripcion,
                "estado" => $estado,
                "flg_tipo" => $flgTipo,
                "data_json" => $data_json,
                "json_envia_web_po" => $json_envia_web_po
            );
            $this->db->insert('log_tramas_sigoplus', $data);
            // _log($this->db->last_query());
            if ($this->db->affected_rows() != 1) {
                //$this->db->trans_rollback();
                throw new Exception('Error al insertar en ptrExpediente');
            } else {
                //$this->db->trans_commit();
                $rpta['error'] = EXIT_SUCCESS;
                $rpta['msj'] = 'Se agrego correctamente!';
            }
        } catch (Exception $e) {
            $rpta['msj'] = $e->getMessage();
            //$this->db->trans_rollback();
        }
        return $rpta;
    }

    function getEstadosDiseno()
    {
        $Query = "  SELECT idEstadoPlan, estadoPlanDesc
					FROM estadoplan
					WHERE idEstadoPlan IN (" . ID_ESTADO_DISENIO . "," . ID_ESTADO_DISENIO_EJECUTADO . "," . ID_ESTADO_DISENIO_PARCIAL . ")
					ORDER BY idEstadoPlan";
        $result = $this->db->query($Query, array());
        return $result;
    }

    /*     * ***************** */

    function execActualizaToro()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->query("SELECT setSAPDetallefromPEPTORO();");
            if ($this->db->trans_status() === TRUE) {
                $data['error'] = EXIT_SUCCESS;
            } else {
                _log('setSAPDetallefromPEPTORO : ', true);
            }
        } catch (Exception $e) {
            $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;
    }

    /*     * ********************************** */

    function existeIndicador($indicador)
    {
        $query = " SELECT COUNT(1) as count
		          FROM planobra WHERE indicador = ?";
        $result = $this->db->query($query, array($indicador));
        return $result->row()->count;
    }

    function getCuadrillaAll()
    {
        $sql = "SELECT idCuadrilla, 
					   idEecc, 
					   idZonal, 
					   descripcion, 
					   estado, 
					   id_usuario,
					   fechaRegistro 
				  FROM cuadrilla";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function sendDataToURL($url, $dataSend)
    {
        $data = array();
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataSend);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            //insert log
            _log('catch sendDataToURL');
        }

        return json_decode($response);
    }

    function getUsuarioCuadrilla()
    {
        $idEec = $this->session->userdata('eeccSession');
        $sql = " SELECT id_usuario, 
						nombre 
				   FROM usuario 
				  WHERE id_perfil = 12
					AND id_eecc = CASE WHEN " . $idEec . " = 0 OR " . $idEec . " = 6 THEN id_eecc 
					                   ELSE " . $idEec . " END";
        $result = $this->db->query($sql);
        return $result;
    }

    function simpleUpdateEstadoPlanObra($itemplan, $dataUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error al modificar el updateEstadoPlanObra');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se Actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function getInfocentralByIdCentral($idCentral)
    {
        $Query = " SELECT idCentral,
                          idTipoCentral,
                          codigo,
                          tipoCentralDesc,
                          idZonal,
                          CASE WHEN flg_subproByNodoCV IS NULL THEN idEmpresaColab
							   ELSE idEmpresaColabCV  END AS idEmpresaColab,
                          jefatura,
                          region,
                          flg_subproByNodoCV,
                          idJefatura,
                          region,
                          idEmpresaColabCV,
                          idJefatura,
                          latitud,
                          longitud,
                          medio_tx,
                          mu_codura,
                          ubicacion,
                          munomura,
                          distrito,
                          idEmpresaColabFuente
                    FROM central  
                   WHERE idCentral = ?";
        $result = $this->db->query($Query, array($idCentral));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getEmpresaColabCvByInt($itemplan)
    {
        $sql = "SELECT DISTINCT c.distrito,
                       e.idEmpresaColab,
                       e.empresaColabDesc,
                       po.idEmpresaColabDiseno
                  FROM planobra po,
                       subproyecto s,
                       central c,
                       empresacolab e
                WHERE po.idCentral = c.idCentral
                  AND CASE WHEN s.idTipoSubProyecto = 1 THEN e.idEmpresaColab = c.idEmpresaColab
                           ELSE e.idEmpresaColab = c.idEmpresaColabCV END 
                  AND po.idSubProyecto = s.idSubProyecto
                  AND po.itemplan      = ?
				  AND po.paquetizado_fg IS NULL
				UNION ALL
				  SELECT DISTINCT c.distrito,
                       e.idEmpresaColab,
                       e.empresaColabDesc,
                       po.idEmpresaColabDiseno
                  FROM planobra po,
                       subproyecto s,
                       pqt_central c,
                       empresacolab e
                WHERE po.idCentralPqt = c.idCentral
                  AND CASE WHEN s.idTipoSubProyecto = 1 THEN e.idEmpresaColab = c.idEmpresaColab
                           ELSE e.idEmpresaColab = c.idEmpresaColabCV END 
                  AND po.idSubProyecto = s.idSubProyecto
                  AND po.itemplan      = ?
				  AND (po.paquetizado_fg = 2 OR po.paquetizado_fg = 1)";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->row_array();
    }

    function getLastItemplanByPrefijoItemplan($preFijoItemplan)
    {
        $Query = "SELECT  CASE WHEN LENGTH(correlativo1) = 1 THEN CONCAT(itemplan,'0000',correlativo1)
                                WHEN LENGTH(correlativo1) = 2 THEN CONCAT(itemplan,'000',correlativo1)
                                WHEN LENGTH(correlativo1) = 3 THEN CONCAT(itemplan,'00',correlativo1)
                                WHEN LENGTH(correlativo1) = 4 THEN CONCAT(itemplan,'0',correlativo1)
                                WHEN LENGTH(correlativo1) = 5 THEN CONCAT(itemplan, correlativo1) ELSE NULL
                          END as new_itemplan
                FROM man_correlativos where itemplan = ?";
        $result = $this->db->query($Query, array($preFijoItemplan));
        if ($result->row() != null) {
            return $result->row_array()['new_itemplan'];
        } else {
            return null;
        }
    }

    function hasSisegoPlanObra($itemplan, $origen)
    {
        $Query = "SELECT    COUNT(1) as count  
                    FROM    sisego_planobra 
                   WHERE    itemplan = ? 
                     AND    origen = ?";
        $result = $this->db->query($Query, array($itemplan, $origen));
        return $result->row()->count;
    }

    function getMaterialesBySubProyecto($idsubProyecto)
    {
        $sql = "SELECT 	sm.id_subproyecto_material, 
                		ma.id_material, 
                		ma.descrip_material 
                FROM 	subproyecto_material sm, material ma
                WHERE 	sm.id_material		=	ma.id_material
                AND 	sm.id_subproyecto 	= 	?";
        $result = $this->db->query($sql, array($idsubProyecto));
        return $result->result();
    }

    function getDataFormularioSisego($itemPlan, $idTipo_obra, $origen)
    {
        $sql = "SELECT s.itemplan,
					 s.origen,
					 s.tipo_obra AS idTipo_obra,
					(SELECT descripcion 
					   FROM tipo_obra 
					  WHERE idtipo_obra = s.tipo_obra)tipo_obra,
					(SELECT GROUP_CONCAT(nodo) 
                       FROM sisego_planobra_x_nodos_fo_oscu
					  WHERE itemplan = s.itemplan) cod_nodos,
					s.nap_nombre,  
					s.nap_nombre,
					s.nap_num_troncal,
					s.nap_cant_hilos_habi,
					s.nap_nodo,
					s.nap_coord_x,
					s.nap_coord_y,
					s.nap_ubicacion,
					s.nap_num_pisos,
					s.nap_zona,
					s.fo_oscu_cant_hilos,
					s.fo_oscu_cant_nodos,
					s.trasla_re_cable_externo,
					s.trasla_re_cable_interno,
					s.fo_tra_cant_hilos,
					s.fo_tra_cant_hilos_hab,
					s.fec_registro,
					s.licencia,
					c.jefatura,
                    e.empresacolabDesc,
					CASE WHEN  NOW() < s.fec_registro + INTERVAL 12 HOUR THEN '#8CE857'
                         ELSE '#F6A5A5' END as color,
					(SELECT nombre 
					   FROM usuario 
					  WHERE id_usuario = s.usuario_registro)usuario,
					f.faseDesc
			 FROM sisego_planobra s,
			      planobra po, 
                  central c, 
                  empresacolab e,
                  fase f
			WHERE s.origen    = ?
			  AND po.itemplan = s.itemplan 
              AND c.idCentral = po.idCentral
              AND po.idFase = f.idFase
              AND c.idEmpresaColab = e.idEmpresaColab
			  AND NOW() < s.fec_registro + INTERVAL 24 HOUR
			  AND s.tipo_obra = COALESCE(?, s.tipo_obra)
			  AND s.itemplan    = COALESCE(?, s.itemplan)";
        $result = $this->db->query($sql, array($origen, $idTipo_obra, $itemPlan));
        return $result->result();
    }

    function cambiarEstadoObra($itemPlan, $arrayData)
    {
        $this->db->where('itemplan', $itemPlan);
        $this->db->update('planobra', $arrayData);

        if ($this->db->affected_rows() == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    function registrarLogPlanObra($array)
    {
        $this->db->insert('log_planobra', $array);

        if ($this->db->affected_rows() == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    function getEstadoAprobCVByItemplan($itemplan)
    {
        $Query = 'SELECT estado_aprob 
                    FROM planobra_detalle_cv 
                   WHERE itemplan = ?';
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['estado_aprob'];
        } else {
            return null;
        }
    }

    function getJefaturaByitemPlan($itemplan)
    {
        $sql = "SELECT ce.jefatura 
				  FROM planobra po,
					   central  ce
				 WHERE po.itemplan = '" . $itemplan . "'
				   AND po.idCentral = ce.idCentral";
        $result = $this->db->query($sql);
        return $result->row_array()['jefatura'];
    }

    /*     * ********PROYECTO PLANTA INTERNA***************************** */

    //harcodeado por czavala pedido owen saravia 5 febrero 2020
    function getAllProyectoPI()
    {
        $Query = "SELECT * FROM
                        proyecto
                    WHERE
                        idProyecto IN (SELECT 
                                subp.idproyecto
                            FROM
                                subproyecto subp
                                #  WHERE
                            #    subp.idTipoPlanta = 2
                            #    AND subp.idSubProyecto IN (568 , 14, 15, 16,196, 198, 199)
                            GROUP BY subp.idproyecto)
							AND idProyecto NOT IN (26)";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getCodigoFase($anio)
    {
        $Query = "  SELECT idFase FROM fase where faseDesc=?";
        $result = $this->db->query($Query, array($anio));

        $codigofase = $result->row_array()['idFase'];

        return $codigofase;
    }

    function getMotivoAll($flgTipo)
    {
        $sql = "SELECT idMotivo,
                       idSisego,
		               UPPER(motivoDesc) as motivoDesc 
				  FROM motivo
				 WHERE flg_tipo = " . $flgTipo . "
				   AND estado = 1
				 ORDER BY motivoDesc asc";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getPlanObraDetalleCVByItemplan($itemplan)
    {
        $Query = " SELECT 	* 
                    FROM    planobra_detalle_cv  cv LEFT JOIN  itemplan_material im ON
                       cv.itemplan = im.itemplan
                    WHERE cv.itemplan =  ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    /*     * ***********************09082018************************************** */

    function existeItemplan($itemplanPE)
    {
        $sql = "SELECT COUNT(1) cant
    	              FROM planobra
    	             WHERE itemplan = '" . $itemplanPE . "' LIMIT 1";
        $result = $this->db->query($sql, array());
        return $result;
    }

    /*     * **********************10082018************** */

    function getPerfiles($codigo)
    {
        $sql = "SELECT desc_perfil FROM perfil per WHERE id_perfil in (" . $codigo . ")";
        $result = $this->db->query($sql);
        if ($result->row() != null) {
            return $result->row()->desc_perfil;
        } else {
            return "";
        }
    }

    /*     * ************************************************************************* */
    /*     * ***********************13082018****************************************** */

    function getAllPerfilessinAdmin()
    {
        $Query = "  SELECT *
	                FROM perfil
	                where not id_perfil in (4)
	                ORDER BY desc_perfil";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getInfoFichaByItemPlanAndEstacion($itemplan, $idEstacion)
    {
        $Query = " SELECT *
                    FROM    ficha_tecnica
                    WHERE   itemplan = ?
                    AND     flg_activo = 1
	                AND    id_estacion = ?";
        $result = $this->db->query($Query, array($itemplan, $idEstacion));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getUsuarioRegistroItemplanPIN()
    {
        $sql = "SELECT l.id_usuario,
					   (SELECT nombre 
						  FROM usuario u 
						 WHERE u.id_usuario = l.id_usuario)nombre  
				  FROM log_planobra l
				 WHERE l.tipoPlanta = 2
				   AND l.actividad = 'ingresar'  
				GROUP BY l.id_usuario";
        $result = $this->db->query($sql);
        return $result->result();
    }

    /*     * **************************modificacion de esatdos itemplan 17-08-2018******************************** */

    function getAllMotivos()
    {
        $Query = "SELECT * from motivo WHERE estado = 1 order by motivoDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getEstadosModItemplan()
    {
        $Query = "  SELECT idEstadoPlan, UPPER(estadoPlanDesc) AS                       estadoPlanDesc
					FROM estadoplan 
					WHERE idEstadoPlan IN (1,2,3,6) 
					ORDER BY idEstadoPlan";
        $result = $this->db->query($Query, array());
        return $result;
    }

    /*     * ********************************************************************************************** */

    function countFichaTecnica($itemplan)
    {
        $sql = "SELECT COUNT(1)as count
				  FROM ficha_tecnica 
				 WHERE itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function getCentralYECCByItemplan($itemplan)
    {
        $Query = 'SELECT   po.itemplan, c.jefatura, e.empresaColabDesc 
                    FROM   planobra po, central c, empresacolab e, subproyecto s
                	WHERE  po.idCentral = c.idCentral
                	AND po.idSubProyecto = s.idSubproyecto
                	AND    CASE WHEN s.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = e.idEmpresaColab ELSE
                	c.idEmpresaColab = e.idEmpresaColab END
                	AND po.itemplan = ?';
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function existeSisego($sisego)
    {
        $sql = "SELECT itemplan, COUNT(1) as count 
		          FROM planobra WHERE indicador = ? 
				   AND idEstadoPlan NOT IN (" . ID_ESTADO_CANCELADO . ", 10) LIMIT 1";
        $result = $this->db->query($sql, array($sisego));
        return $result->row_array();
    }

    ///////////////////////////26082018///////////////////////////////
    function getAnioActualAnterior()
    {

        $query = "SELECT YEAR(CURDATE()) as anioactual ,(year(curdate())-1) as anioanterior";
        $result = $this->db->query($query, array());
        return $result;
    }

    function getJefatura()
    {
        $query = "SELECT jefatura 
			  FROM central
			GROUP BY jefatura";
        $result = $this->db->query($query);
        return $result;
    }

    ////////////////////////////////////////////////////////////////

    function getDataPlantaInterna($idEstadoPlan, $tipoPlanta, $fechaIn, $fechaFin, $idEecc)
    {
        $sql = "SELECT paz.itemplan, 
						po.nombreProyecto,
						p.proyectoDesc,
						s.idSubProyecto,
						c.codigo,
						(SELECT u.nombre
                           FROM log_planobra l, 
                         	    usuario u 
                          WHERE l.tipoPlanta = 2 
                            AND l.actividad like 'ingresar%' 
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario)as usuarioRegis,
                        (SELECT u.nombre
                           FROM log_planobra l, 
                         	    usuario u 
                          WHERE l.actividad IN ('Terminar obra-Validado','Terminar obra')
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario 
                          limit 1)as usuarioTerm,  
						s.subProyectoDesc,
						po.fechaPreLiquidacion,
						po.fechaEjecucion,
						c.idZonal,
						z.zonalDesc,
						e.empresaColabDesc,
						paz.ptr, 
						FORMAT(SUM(paz.costo_mo),2)costo_mo, 
						FORMAT(SUM(paz.costo_mat),2)costo_mat, 
						FORMAT(SUM(paz.total),2)total
				 FROM   ptr_x_actividades_x_zonal paz, 
						planobra po,
						subproyecto s,
						proyecto p,
						central c,
						empresacolab e,
						zonal z,
						ptr_planta_interna ppi
				WHERE paz.itemplan = po.itemplan
					AND po.idEstadoPlan = " . $idEstadoPlan . "
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND s.idTipoPlanta = " . $tipoPlanta . "
					AND po.idCentral = c.idCentral
					AND c.idZonal    = z.idZonal
					AND e.idEmpresaColab = c.idEmpresaColab
					AND c.idEmpresaColab = COALESCE(?, c.idEmpresaColab)
					AND CASE WHEN ? IS NOT NULL AND ? IS NULL     THEN po.fechaEjecucion BETWEEN '" . $fechaIn . "' AND NOW() 
							 WHEN ? IS NOT NULL AND ? IS NOT NULL THEN po.fechaEjecucion BETWEEN '" . $fechaIn . "' AND '" . $fechaFin . "'
							 WHEN ? IS NULL     AND ? IS NOT NULL THEN po.fechaEjecucion <= '" . $fechaFin . "'
							 ELSE po.fechaEjecucion = po.fechaEjecucion END
					AND po.has_log_pi is null
					AND po.paquetizado_fg is null
					AND ppi.itemplan = po.itemplan
                    AND ppi.rangoPtr <> 6
                    AND ppi.ptr = paz.ptr	
					GROUP BY po.itemplan, po.idSubProyecto
					UNION ALL 
					SELECT paz.itemplan, 
						po.nombreProyecto,
						p.proyectoDesc,
						s.idSubProyecto,
						c.codigo,
						(SELECT u.nombre
                           FROM log_planobra l, 
                         	    usuario u 
                          WHERE l.tipoPlanta = 2 
                            AND l.actividad like 'ingresar%' 
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario)as usuarioRegis,
                        (SELECT u.nombre
                           FROM log_planobra l, 
                         	    usuario u 
                          WHERE l.actividad IN ('Terminar obra-Validado','Terminar obra')
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario 
                          limit 1)as usuarioTerm,  
						s.subProyectoDesc,
						po.fechaPreLiquidacion,
						po.fechaEjecucion,
						c.idZonal,
						z.zonalDesc,
						e.empresaColabDesc,
						paz.ptr, 
						FORMAT(SUM(paz.costo_mo),2)costo_mo, 
						FORMAT(SUM(paz.costo_mat),2)costo_mat, 
						FORMAT(SUM(paz.total),2)total
				 FROM   ptr_x_actividades_x_zonal paz, 
						planobra po,
						subproyecto s,
						proyecto p,
						pqt_central c,
						empresacolab e,
						zonal z,
						ptr_planta_interna ppi
				WHERE paz.itemplan = po.itemplan
					AND po.idEstadoPlan = " . $idEstadoPlan . "
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND s.idTipoPlanta = " . $tipoPlanta . "
					AND po.idCentralPqt = c.idCentral
					AND c.idZonal    = z.idZonal
					AND e.idEmpresaColab = c.idEmpresaColab
					AND c.idEmpresaColab = COALESCE(?, c.idEmpresaColab)
					AND CASE WHEN ? IS NOT NULL AND ? IS NULL     THEN po.fechaEjecucion BETWEEN '" . $fechaIn . "' AND NOW() 
							 WHEN ? IS NOT NULL AND ? IS NOT NULL THEN po.fechaEjecucion BETWEEN '" . $fechaIn . "' AND '" . $fechaFin . "'
							 WHEN ? IS NULL     AND ? IS NOT NULL THEN po.fechaEjecucion <= '" . $fechaFin . "'
							 ELSE po.fechaEjecucion = po.fechaEjecucion END
					AND po.has_log_pi is null 
					AND (po.paquetizado_fg  = 2 or po.paquetizado_fg  = 1)
					AND ppi.itemplan = po.itemplan
                    AND ppi.rangoPtr <> 6
                    AND ppi.ptr = paz.ptr					
					GROUP BY po.itemplan, po.idSubProyecto";
        $result = $this->db->query($sql, array($idEecc, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $idEecc, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $fechaIn, $fechaFin));
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    function updateFlgCancelacion($itemplan, $data)
    {
        $this->db->where('itemplan', $itemplan);
        $this->db->update('planobra', $data);

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('1) Error interno al solicitar cancelacion.');
        } else {
            return array("error" => EXIT_SUCCESS, "msj" => 'OPERACION REALIZADA CON EXITO');
        }
    }

    function getDataBandejaSolicitud($flgSolicitud)
    {
        $sql = "SELECT po.itemplan, 
						po.fechaSolicitud, 
						po.idEstadoPlan,
						e.estadoPlanDesc,
						c.jefatura,
						s.subProyectoDesc,
						em.empresaColabDesc,
						f.faseDesc
				  FROM planobra po,
					   subproyecto s,
					   estadoplan e,
					   central c,
					   empresacolab em,
					   fase f
				 WHERE po.flgSolicitudCancelacion = " . $flgSolicitud . "
				   AND po.idEstadoPlan NOT IN (4,5,6,10)
				   AND po.idSubProyecto = s.idSubProyecto
			       AND po.idEstadoPlan = e.idEstadoPlan
			       AND po.idFase = f.idFase
				   AND c.idCentral = po.idCentral
				   AND c.idEmpresaColab = em.idEmpresaColab
				   AND (po.paquetizado_fg = 1 or po.paquetizado_fg is null)
                                    
				   UNION ALL
				   SELECT po.itemplan, 
						po.fechaSolicitud, 
						po.idEstadoPlan,
						e.estadoPlanDesc,
						c.jefatura,
						s.subProyectoDesc,
						em.empresaColabDesc,
						f.faseDesc
				  FROM planobra po,
					   subproyecto s,
					   estadoplan e,
					   pqt_central c,
					   empresacolab em,
					   fase f
				 WHERE (po.flgSolicitudCancelacion = " . $flgSolicitud . ")
				   AND po.idEstadoPlan NOT IN (4,5,6,10)
				   AND po.idSubProyecto = s.idSubProyecto
			       AND po.idEstadoPlan = e.idEstadoPlan
			       AND po.idFase = f.idFase
				   AND c.idCentral = po.idCentralPqt
				   AND c.idEmpresaColab = em.idEmpresaColab
				   AND po.paquetizado_fg = 2";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getALLOLDProyectosCV()
    {
        $Query = 'SELECT * FROM planobra_detalle_cv_pre';
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getALLODFCV()
    {
        $Query = 'SELECT * FROM planobra_detalle_cv_odf';
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getParalizacion($itemplan, $flgActivo)
    {
        $sql = "SELECT p.itemplan, 
					   p.flg_activo, 
					   p.fechaRegistro,
					   e.estadoPlanDesc,
					   m.motivoDesc as motivo,
					   p.idMotivo,
					   p.comentario,
					   CASE WHEN m.flg_origen = 1 THEN 'SISEGO WEB'
					        ELSE 'SINFIX' END as origen,
					   p.ubicacionEvidencia,
					   CASE WHEN p.idUsuario IS NULL THEN p.nombreUsuarioTrama 
					        ELSE (SELECT u.nombre 
									FROM usuario u
								   WHERE u.id_usuario= p.idUsuario) END as usuario,
					   f.faseDesc
				  FROM paralizacion p,
					   planobra po,
					   estadoplan e,
					   motivo m,
					   fase f
				 WHERE po.itemplan = p.itemplan
				   AND m.idMotivo  = p.idMotivo
				   AND po.idFase = f.idFase
				   AND e.idEstadoPlan = po.idEstadoPlan
				   AND flg_activo = " . $flgActivo . "
				  -- AND p.flgEstado = " . ORIGEN_SINFIX . "
				   AND p.itemplan = COALESCE(?, p.itemplan) AND NOT e.idEstadoPlan = 5";
        $result = $this->db->query($sql, array($itemplan));
        return $result->result();
    }

    function countParalizados($itemplan, $flg, $origen)
    {
        $sql = "SELECT COUNT(1) count
				  FROM paralizacion
				 WHERE itemplan = '" . $itemplan . "'
				   AND flg_activo = " . $flg;
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function countParalizadosPorPresupuesto($itemplan, $flg)
    {
        $sql = "SELECT COUNT(1) count
				  FROM paralizacion
				 WHERE itemplan = '" . $itemplan . "'
				   AND flg_activo = " . $flg . " 
	               AND idMotivo IN (11,42)";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function updateFlgParalizacion($itemplan, $flgActual, $arrayData)
    {
        $this->db->where('itemplan', $itemplan);
        $this->db->where('flg_activo', $flgActual);
        $this->db->update('paralizacion', $arrayData);

        if ($this->db->affected_rows() != 1) {
            throw new Exception('No se puede revertir.');
        } else {
            return array("error" => EXIT_SUCCESS, "msj" => 'OPERACION REALIZADA CON EXITO');
        }
    }

    function updateUbicacion($itemplan, $flgActivo, $ubicacion)
    {
        $this->db->where('itemplan', $itemplan);
        $this->db->where('flg_activo', $flgActivo);
        $this->db->update('paralizacion', array('ubicacionEvidencia' => $ubicacion));

        if ($this->db->affected_rows() != 1) {
            throw new Exception('no se actualizo la ubicaci&oacute;n.');
        } else {
            return array("error" => EXIT_SUCCESS, "msj" => 'OPERACION REALIZADA CON EXITO');
        }
    }

    function getIdMotivo($desc)
    {
        $sql = "SELECT idMotivo 
		          FROM motivo 
				 WHERE motivoDesc like '" . $desc . "%'";
        $result = $this->db->query($sql);
        return $result->row_array()['idMotivo'];
    }

    //////////////////////////////////24092018//////////////////////////
    function getAllSubProyectoPI()
    {
        $Query = "SELECT idSubProyecto,subProyectoDesc
	                FROM subproyecto
				   WHERE idTipoPlanta = 2 
	             ORDER BY subProyectoDesc";
        $result = $this->db->query($Query);
        return $result;
    }

    function getAllVREECC()
    {
        $Query = "  SELECT * 
                    FROM empresacolab
                    WHERE idEmpresaColab IN (7,8)
                    ORDER BY empresaColabDesc";
        $result = $this->db->query($Query, array());
        return $result;
    }

    ///////////////////////////28092018////////////////////////////////
    function getAnioConstCV()
    {
        $query = "SELECT tab1.aniocons 
    			from (select distinct year(date(fec_termino_constru)) as aniocons 
						from planobra_detalle_cv 
						where (not fec_termino_constru is null 
						and fec_termino_constru!='')) as tab1 
						order by tab1.aniocons";

        $result = $this->db->query($query, array());
        return $result;
    }

    ///////////////////////01102018///////////////////////////////////
    function getItemplanArchivos()
    {
        $Query = "  SELECT itemplan, idEstadoPlan
                    FROM planobra
                    WHERE idestadoplan IN (3,4,5,9,6)
                    ORDER BY itemplan";
        $result = $this->db->query($Query, array());
        return $result;
    }

    /////////////////03102018/////////////////////////////////////

    function getProyectoxTipoPlanta($idTipoPlanta)
    {
        $Query = "SELECT idproyecto, proyectoDesc 
	                FROM proyecto
				   WHERE idproyecto in 
				   			(select idproyecto 
				   			   from subproyecto 
				   			  where idTipoPlanta = ?)
	             ORDER BY proyectoDesc";
        $result = $this->db->query($Query, array($idTipoPlanta));
        return $result;
    }

    function getListItemplanxEecc($idEcc)
    {
        $sql = "SELECT itemplan 
				  FROM planobra po, 
					   central c,
					   subproyecto s
				 WHERE po.idCentral = c.idCentral
				   AND s.idSubProyecto   = po.idSubProyecto
   				   AND idTipoPlanta = 1
				   AND po.idEstadoPlan IN (" . ID_ESTADO_TERMINADO . ", " . ID_ESTADO_PRE_LIQUIDADO . ", " . ID_ESTADO_PLAN_EN_OBRA . ", " . ID_ESTADO_DISENIO_PARCIAL . ", " . ID_ESTADO_TRUNCO . ", " . ID_ESTADO_CANCELADO . ")
				   AND c.idEmpresaColab = CASE WHEN $idEcc IN (0,6) THEN po.idEmpresaColab
				                                ELSE $idEcc END";
        $result = $this->db->query($sql);
        return $result->result();
    }

    // function getPtrByItemplan($itemplan) {
    //     $ideecc  = $this->session->userdata("eeccSession");
    // 	$sql = "SELECT *, CONCAT(ptr,' (',(SELECT estacionDesc FROM estacion WHERE idEstacion = t.idEstacion), ')')ptrEstacion 
    // 	          FROM (
    // 	                SELECT                
    //                         CASE WHEN      
    //                                  (SELECT ptr 
    //                                     FROM solicitud_vale_reserva s 
    //                                    WHERE s.ptr = w.ptr
    //                                       limit 1) IS NOT NULL THEN CASE WHEN (SELECT ptr 
    //                                                                             FROM solicitud_vale_reserva s 
    //                                                                            WHERE s.ptr = w.ptr
    //                                                                              AND s.fecha_atencion IS NOT NULL 
    //                                                                               limit 1) THEN w.ptr
    //                                                                      ELSE NULL END      
    //                              ELSE w.ptr END ptr,
    // 							SUBSTRING_INDEX(SUBSTRING_INDEX(vr, '|', 1),':',-1)vr,
    // 							c.jefatura,
    // 							e.empresaColabDesc,
    // 							(SELECT GROUP_CONCAT(je.codAlmacen,'|',je.codCentro,'|', je.idJefatura, '|', je.idEmpresaColab) 
    // 							FROM jefatura_sap j, 
    // 									jefatura_sap_x_empresacolab je 
    // 							WHERE j.idJefatura = je.idJefatura
    // 								AND je.idEmpresacolab = e.idEmpresacolab
    // 								AND CASE WHEN j.idZonal IS NULL THEN c.jefatura = j.descripcion 
    // 											ELSE j.idZonal = c.idZonal END )dataJefaturaEmp,
    // 						 ea.idEstacion,
    // 						 SUBSTRING_INDEX(w.est_innova, '-', 1) est_innova
    // 					FROM (web_unificada w,
    // 						  detalleplan dp, 
    // 						  planobra po,
    // 						  central c,
    // 						  empresacolab e,
    // 						  subproyectoestacion sp,
    // 						  estacionarea ea,
    // 						  area a) LEFT JOIN itemplan_expediente ie ON (ie.itemplan = dp.itemplan) AND (ea.idEstacion = ie.idEstacion) 
    // 					WHERE w.ptr = dp.poCod
    // 					AND sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
    // 					AND sp.idEstacionarea        = ea.idEstacionArea
    // 					AND a.idArea                 = ea.idArea
    // 					AND a.tipoArea 			     = 'MAT'
    // 					AND c.idCentral 			 = po.idCentral
    // 					AND c.idEmpresaColab 		 = e.idEmpresaColab
    // 					AND ea.idEstacion <> 1 
    // 					AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN c.idEmpresaColab = c.idEmpresaColab
    //                         WHEN ".$ideecc." IN (SELECT idSubProyecto 
    //                                                 FROM subproyecto 
    //                                                WHERE idProyecto = 5)
    //                         THEN c.idEmpresaColabCV =  ".$ideecc."               
    //                          ELSE c.idEmpresaColab = ".$ideecc." END
    // 					AND po.itemplan 			 = dp.itemplan
    // 					AND w.est_innova NOT IN ('08 - OBRA LIQUIDADA PARA PAGO.', '06 - OBRA INSPECCIONADA y CERTIFICADA')
    // 					AND po.idEstadoPlan IN (11,3, 4, 9, 10, 6)
    // 					AND (ie.estado IS NULL OR ie.estado <> 'ACTIVO' AND ie.estado_final <> 'FINALIZADO')
    // 					AND dp.itemplan = '".$itemplan."')t";
    // 	$result = $this->db->query($sql); 
    // 	return $result->result();  
    // }

    function getPtrByItemplan($itemplan)
    {
        $ideecc = $this->session->userdata("eeccSession");

        $sql = "SELECT *, CONCAT(ptr,' (',(SELECT estacionDesc FROM estacion WHERE idEstacion = t.idEstacion), ')')ptrEstacion 
		          FROM (
		                SELECT                
                            CASE WHEN      
                                     (SELECT ptr 
                                        FROM solicitud_vale_reserva s 
                                       WHERE s.ptr = dp.poCod
                                          limit 1) IS NOT NULL THEN CASE WHEN (SELECT COUNT(1) 
                                                                                 FROM solicitud_vale_reserva s 
                                                                                WHERE s.ptr = dp.poCod
                                                                                  AND s.fecha_atencion IS NULL 
                                                                                  limit 1) > 0 THEN NULL
                                                                         ELSE dp.poCod END   
                                 ELSE dp.poCod END ptr,
					
								SUBSTRING_INDEX(SUBSTRING_INDEX(vr, '|', 1),':',-1)vr,
								c.jefatura,
								e.empresaColabDesc,
								(SELECT GROUP_CONCAT(je.codAlmacen,'|',je.codCentro,'|', je.idJefatura, '|', je.idEmpresaColab) 
								FROM jefatura_sap j, 
										jefatura_sap_x_empresacolab je 
								WHERE j.idJefatura = je.idJefatura
									AND je.idEmpresacolab = e.idEmpresacolab
									AND CASE WHEN j.idZonal IS NULL THEN c.jefatura = j.descripcion 
												ELSE j.idZonal = c.idZonal END )dataJefaturaEmp,
							 ea.idEstacion,
							 SUBSTRING_INDEX(w.est_innova, '-', 1) est_innova,
							 po.idSubProyecto
						 FROM (
                                (
								 detalleplan dp LEFT JOIN web_unificada w ON(w.ptr = dp.poCod)
							    ) LEFT JOIN planobra_po ppo ON (ppo.codigo_po = dp.poCod),
								planobra po,
							  	central c,
							  	empresacolab e,
							  	subproyectoestacion sp,
							  	estacionarea ea,
							  	area a
							  ) LEFT JOIN itemplan_expediente ie ON (ie.itemplan = dp.itemplan) AND (ea.idEstacion = ie.idEstacion) 
						WHERE sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
						AND sp.idEstacionarea        = ea.idEstacionArea
						AND a.idArea                 = ea.idArea
						AND a.tipoArea 			     = 'MAT'
						AND c.idCentral 			 = po.idCentral
						AND c.idEmpresaColab 		 = e.idEmpresaColab
						AND ea.idEstacion <> 1 
						AND CASE WHEN " . $ideecc . " = 0 OR " . $ideecc . " = 6 THEN c.idEmpresaColab = c.idEmpresaColab
                            WHEN " . $ideecc . " IN (SELECT idSubProyecto 
                                                    FROM subproyecto 
                                                   WHERE idProyecto = 5)
                            THEN c.idEmpresaColabCV =  " . $ideecc . "               
                             ELSE c.idEmpresaColab = " . $ideecc . " END
						AND po.itemplan 			 = dp.itemplan
						AND CASE WHEN w.ptr IS NOT NULL THEN 
									  w.est_innova NOT IN ('08 - OBRA LIQUIDADA PARA PAGO.', '06 - OBRA INSPECCIONADA y CERTIFICADA')
								 ELSE ppo.estado_po IN (3,4) END
					    AND po.idEstadoPlan IN (11,3, 4, 9, 10, 6, 5, 21)
						AND (ie.estado IS NULL OR ie.estado <> 'ACTIVO' AND ie.estado_final <> 'FINALIZADO')
						AND dp.itemplan = '" . $itemplan . "')t";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getPtrByItemplanPqt($itemplan)
    {
        $ideecc = $this->session->userdata("eeccSession");

        $sql = "SELECT *, CONCAT(ptr,' (',(SELECT estacionDesc FROM estacion WHERE idEstacion = t.idEstacion), ')')ptrEstacion 
		          FROM (
		                SELECT                
                            CASE WHEN      
                                     (SELECT ptr 
                                        FROM solicitud_vale_reserva s 
                                       WHERE s.ptr = dp.poCod
                                          limit 1) IS NOT NULL THEN CASE WHEN (SELECT COUNT(1) 
                                                                                 FROM solicitud_vale_reserva s 
                                                                                WHERE s.ptr = dp.poCod
                                                                                  AND s.fecha_atencion IS NULL 
                                                                                  limit 1) > 0 THEN NULL
                                                                         ELSE dp.poCod END   
                                 ELSE dp.poCod END ptr,
					
								SUBSTRING_INDEX(SUBSTRING_INDEX(vr, '|', 1),':',-1)vr,
								c.jefatura,
								e.empresaColabDesc,
								(SELECT GROUP_CONCAT(je.codAlmacen,'|',je.codCentro,'|', je.idJefatura, '|', je.idEmpresaColab) 
								FROM jefatura_sap j, 
										jefatura_sap_x_empresacolab je 
								WHERE j.idJefatura = je.idJefatura
									AND je.idEmpresacolab = e.idEmpresacolab
									AND CASE WHEN j.idZonal IS NULL THEN c.jefatura = j.descripcion 
												ELSE j.idZonal = c.idZonal END )dataJefaturaEmp,
							 ea.idEstacion,
							 SUBSTRING_INDEX(w.est_innova, '-', 1) est_innova,
							 po.idSubProyecto
						 FROM (
                                (
								 detalleplan dp LEFT JOIN web_unificada w ON(w.ptr = dp.poCod)
							    ) LEFT JOIN planobra_po ppo ON (ppo.codigo_po = dp.poCod),
								planobra po,
							  	pqt_central c,
							  	empresacolab e,
							  	subproyectoestacion sp,
							  	estacionarea ea,
							  	area a
							  ) LEFT JOIN itemplan_expediente ie ON (ie.itemplan = dp.itemplan) AND (ea.idEstacion = ie.idEstacion) 
						WHERE sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
						AND sp.idEstacionarea        = ea.idEstacionArea
						AND a.idArea                 = ea.idArea
						AND a.tipoArea 			     = 'MAT'
						AND c.idCentral 			 = po.idCentralPqt
						AND c.idEmpresaColab 		 = e.idEmpresaColab
						AND ea.idEstacion <> 1 
						AND CASE WHEN " . $ideecc . " = 0 OR " . $ideecc . " = 6 THEN c.idEmpresaColab = c.idEmpresaColab
                            WHEN " . $ideecc . " IN (SELECT idSubProyecto 
                                                    FROM subproyecto 
                                                   WHERE idProyecto = 5)
                            THEN c.idEmpresaColabCV =  " . $ideecc . "               
                             ELSE c.idEmpresaColab = " . $ideecc . " END
						AND po.itemplan 			 = dp.itemplan
						AND CASE WHEN w.ptr IS NOT NULL THEN 
									  w.est_innova NOT IN ('08 - OBRA LIQUIDADA PARA PAGO.', '06 - OBRA INSPECCIONADA y CERTIFICADA')
								 ELSE ppo.estado_po IN (3,4) END
					    AND po.idEstadoPlan IN (11,3, 4, 9, 10, 6, 5, 21)
						AND (ie.estado IS NULL OR ie.estado <> 'ACTIVO' AND ie.estado_final <> 'FINALIZADO')
						AND dp.itemplan = '" . $itemplan . "')t";
        $result = $this->db->query($sql);

        return $result->result();
    }

    function getVrByPtr($ptr)
    {
        $sql = "SELECT vale_reserva as vr
				  FROM web_unificada_det
				 WHERE ptr ='" . $ptr . "'
				 UNION ALL 
                SELECT vale_reserva  
                  FROM planobra_po 
                 WHERE codigo_po ='" . $ptr . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['vr'];
    }

    function getCountPtrPI($itemplan)
    {
        $sql = "SELECT COUNT(1) count
		          FROM ptr_planta_interna 
				 WHERE itemplan ='" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row()->count;
    }

    function getTipoSolicitud()
    {
        $sql = "SELECT idTipoSolicitud,
    				   descripcion,
    				   flg_activo
    			  FROM tipo_solicitud
    			 WHERE flg_activo = " . FLG_ACTIVO;
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getCmbTipoSolicitudRegistro($cantidad = null)
    {
        $sql = "SELECT idTipoSolicitud,
    				   descripcion,
    				   flg_activo
    			  FROM tipo_solicitud
    			 WHERE CASE WHEN ? = '0.00' OR ? IS NULL OR ? = '0' THEN idTipoSolicitud = 1
                            ELSE idTipoSolicitud IN (5,2,4) END				 
				   AND flg_activo = " . FLG_ACTIVO;
        $result = $this->db->query($sql, array($cantidad, $cantidad, $cantidad));
        return $result->result();
    }

    function getEECCXCentralXSubProyecto($idCentral)
    {
        $sql = "   SELECT c.idEmpresaColab,
		                  empresacolab.empresaColabDesc,
		                  c.idEmpresaColabCV,
		                  eecc.empresaColabDesc AS empresaColabCV                             
                     FROM central c
                LEFT JOIN empresacolab      ON  c.idEmpresaColab = empresacolab.idEmpresaColab
                LEFT JOIN empresacolab eecc ON  c.idEmpresaColabCV = eecc.idEmpresaColab
                   WHERE c.idcentral = ? ";
        $result = $this->db->query($sql, array($idCentral));
        return $result;
    }

    function getEECCXCentralXSubProyectoPqt($idCentral)
    {
        $sql = "   SELECT c.idEmpresaColab,
		                  empresacolab.empresaColabDesc,
		                  c.idEmpresaColabCV,
		                  eecc.empresaColabDesc AS empresaColabCV                             
                     FROM pqt_central c
                LEFT JOIN empresacolab      ON  c.idEmpresaColab = empresacolab.idEmpresaColab
                LEFT JOIN empresacolab eecc ON  c.idEmpresaColabCV = eecc.idEmpresaColab
                   WHERE c.idcentral = ? ";
        $result = $this->db->query($sql, array($idCentral));
        return $result;
    }

    function getIdCuotaAgenda($idEmpresaColab, $jefatura, $idBandaHoraria)
    {
        $sql = "SELECT idCuotaAgenda 
				  FROM cuotas_agenda
			     WHERE idEmpresaColab = " . $idEmpresaColab . " 
				   AND jefatura = '" . $jefatura . "'
				   AND idBandaHoraria = " . $idBandaHoraria;
        $result = $this->db->query($sql);
        return $result->row();
    }

    function getMotivoAllByOrigen($flgOrigen)
    {
        $sql = "SELECT idMotivo,    UPPER(motivoDesc) as motivoDesc
                FROM motivo
                WHERE flg_origen = ?
                order by motivoDesc";
        $result = $this->db->query($sql, array($flgOrigen));
        return $result->result();
    }

    function getDataSiomAll($itemplan)
    {
        $sql = "SELECT s.itemplan, 
							e.estacionDesc,
							s.codigoSiom,
							e.idEstacion,
                            s.ultimo_estado,
                            s.fecha_ultimo_estado
				   FROM siom_obra s, estacion e 
				  WHERE s.idEstacion = e.idEstacion
					AND s.itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function countSiomByItemplan($itemplan)
    {
        $sql = "SELECT COUNT(1)count
				  FROM siom_obra 
				 WHERE itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function hasDisenoAdjudicado($itemplan)
    {
        $Query = "SELECT count(1) as cont FROM pre_diseno WHERE itemplan = ? and idEstacion = 5 LIMIT 1";
        $result = $this->db->query($Query, array($itemplan));
        return $result->row()->cont;
    }

    function getAllIdEstacionAreaByAreaBySubPro($idSubPro)
    {
        $Query = "  SELECT 	distinct a.idArea, a.areaDesc, ea.idEstacionArea
                    FROM 	subproyectoestacion se, estacionarea ea, area a 
                    WHERE 	se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea = a.idArea
                    AND 	se.idSubProyecto = ?
                    ORDER BY tipoArea, areaDesc";
        $result = $this->db->query($Query, array($idSubPro));
        return $result;
    }

    function getIdEstacion($descEstacion)
    {
        $sql = "SELECT idEstacion 
		          FROM estacion 
				 WHERE estacionDesc = UPPER('" . $descEstacion . "')";
        $result = $this->db->query($sql);
        return $result->row_array()['idEstacion'];
    }

    function getPO($itemplan, $cont = 1)
    {
        $sql = "SELECT CONCAT(" . ANIO_CREATE_PO . ",'-',
								(SELECT CONCAT(t.idProyecto, t.idJefatura)
								  FROM (
											SELECT CASE WHEN LENGTH(s.idProyecto ) = 1 THEN  CONCAT('0',s.idProyecto)
														 ELSE s.idProyecto END AS idProyecto, 
												   CASE WHEN LENGTH(j.idJefatura ) = 1 THEN  CONCAT('0',j.idJefatura)
														ELSE j.idJefatura END AS idJefatura    
											   FROM planobra po, 
													subproyecto s,
													jefatura j,
													central c
											  WHERE s.idSubProyecto = po.idSubProyecto 
												AND c.idCentral     = po.idCentral
												AND j.descripcion   = c.jefatura
												AND itemplan        = '" . $itemplan . "'
												GROUP BY c.jefatura, po.itemplan
									   )t
								),
								(SELECT CASE WHEN LENGTH(t.correlativo) = 1 THEN CONCAT('000',t.correlativo)  
											 WHEN LENGTH(t.correlativo) = 2 THEN CONCAT('00',t.correlativo)
											 WHEN LENGTH(t.correlativo) = 3 THEN CONCAT('0',t.correlativo)
											 ELSE t.correlativo END
								  FROM(
										SELECT COUNT(1)+" . $cont . " correlativo
										  FROM planobra_po
									  )t
								)
							 )po";
        $result = $this->db->query($sql);
        return $result->row_array()['po'];
    }

    function getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, $tipoArea)
    {
        $sql = " SELECT idSubProyectoEstacion 
				   FROM subproyectoestacion 
				  WHERE idSubProyecto = ( SELECT idSubProyecto 
										    FROM planobra 
										   WHERE itemplan = '" . $itemplan . "') 
					AND idEstacionArea = (
											SELECT idEstacionArea 
											  FROM estacionarea 
											 WHERE idEstacion = " . $idEstacion . "
											   AND idArea IN(
																SELECT idArea 
																  FROM area 
																 WHERE tipoArea = '" . $tipoArea . "'
															)
										)";
        $result = $this->db->query($sql);
        return $result->row_array()['idSubProyectoEstacion'];
    }

    function countItmeplan($itemplan)
    {
        $sql = "SELECT COUNT(1) count
		          FROM planobra 
				 WHERE itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function countMaterial($codMaterial)
    {
        $sql = "SELECT COUNT(1)count
				  FROM material
				 WHERE id_material = '" . $codMaterial . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function countPOByItemplanAndEstacion($itemplan, $idEstacion, $from)
    {
        $sql = "SELECT COUNT(1)count
				  FROM planobra_po ppo,
					   planobra  po
				 WHERE ppo.itemplan = po.itemplan  
				   AND po.idSubProyecto NOT IN(SELECT idSubProyecto 
												 FROM subproyecto 
												WHERE idProyecto IN (5, 4)) -- FTT Y OBRAS PUBLICAS 
				   AND ppo.idEstacion = " . $idEstacion . "
				   AND ppo.from       = " . $from . "
				   AND ppo.estado_po NOT IN (" . PO_CANCELADO . "," . PO_PRECANCELADO . ")
				   AND po.itemplan    = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function getKitByIdSubProyecto($idSubProyecto, $idEstacion)
    {
        $sql = "SELECT k.id_material,
					   k.idSubProyecto,
					   k.cantidad_kit,
					   k.factor_porcentual,
					   m.descrip_material,
					   m.costo_material,
					   m.estado_material,
					   FORMAT((m.costo_material * k.cantidad_kit), 2) as totalMaterial
				  FROM kit_material k,
				       material m
				 WHERE k.idSubProyecto = ?
				   AND k.idEstacion    = ?
				   AND m.id_material  = k.id_material";
        $result = $this->db->query($sql, array($idSubProyecto, $idEstacion));
        return $result->result_array();
    }

    function getAllMaterial($idSubProyecto, $idEstacion)
    {
        $sql = "SELECT DISTINCT m.id_material, 
						m.descrip_material, 
						m.costo_material, 
						m.estado_material, 
						m.flg_tipo,
						CASE WHEN t.kitIdMaterial IS NOT NULL THEN '#A9F5A9' 
							ELSE null END colorSelec,
						CASE WHEN m.flg_tipo = 0 THEN 'NO BUCLE'
									ELSE 'BUCLE' END tipo,
						t.kitIdMaterial,
						CASE WHEN m.flg_tipo = 0 THEN 'NO BUCLE'
							ELSE 'BUCLE' END tipo,
						t.cantidad_kit,
						t.factor_porcentual	 	 
				  FROM material m LEFT JOIN (  
					   SELECT k.id_material as kitIdMaterial,
							  k.factor_porcentual,
							  k.idEstacion,
							  k.cantidad_kit,
							  k.idSubProyecto
						 FROM kit_material k
						WHERE CASE WHEN '" . $idEstacion . "' = '' THEN k.id_material = k.id_material 
						           ELSE k.idEstacion = '" . $idEstacion . "' AND k.idSubProyecto = '" . $idSubProyecto . "' END 
					    GROUP BY k.id_material )t ON(m.id_material = t.kitIdMaterial)
				  WHERE m.flg_tipo = " . FLG_MATERIAL_NO_BUCLE . "
				    AND m.descrip_material <> ''

				  ORDER BY m.descrip_material ASC";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getMaterialByPo($po, $itemplan, $idEstacion)
    {
        $sql = "SELECT t.* 
		          FROM (
						SELECT po.idSubProyecto, 
							ma.id_material AS id_material, 
							ma.descrip_material,
							pod.cantidad_final AS cantidad_kit, 
							pod.codigo_po,
							CASE WHEN pod.cantidad_final IS NULL 
									THEN pod.cantidad_ingreso ELSE pod.cantidad_final END cantidad_ingreso,
							ROUND(ma.costo_material,2) AS costo_material,
							FORMAT((ma.costo_material * pod.cantidad_final), 2) as totalMaterial,
							NULL AS flg_solicitud,
                            ppo.pep1,
							ppo.costo_total
						FROM planobra_po_detalle pod,
							planobra po,
							material ma,
                            planobra_po ppo
						WHERE po.itemplan      = '" . $itemplan . "'
                         AND ppo.codigo_po     = pod.codigo_po
                         AND ppo.itemplan      = po.itemplan
						AND pod.codigo_po    = '" . $po . "'
                        AND pod.codigo_material = ma.id_material
						UNION ALL
						SELECT k.idSubProyecto, 
								k.id_material,
								ma.descrip_material,
								k.cantidad_kit,
								'', 
								0,
								ROUND(ma.costo_material,2) AS costo_material,
								FORMAT((ma.costo_material * k.cantidad_kit), 2) as totalMaterial,
								k.flg_solicitud,
                                (SELECT pep1 FROM planobra_po WHERE codigo_po = '" . $po . "') pep1,
								(SELECT costo_total FROM planobra_po WHERE codigo_po = '" . $po . "')costo_total
						FROM kit_material k,
								planobra po,
								material ma
						WHERE k.idSubProyecto = po.idSubProyecto
							AND po.itemplan    = '" . $itemplan . "'
							AND ma.id_material = k.id_material 
							AND k.idEstacion  = " . $idEstacion . "
							AND flg_solicitud = 1
						)t
				GROUP BY t.id_material";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function quitarLimiteGroupConcat()
    {
        $sql = "SET @@session.group_concat_max_len = 10000; ";
        $result = $this->db->query($sql);
        return $result;
    }

    public function insertarLOGPO($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_planobra_po', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_planobra_po');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getEstacionBySubProyecto($idSubProyecto)
    {
        $sql = "SELECT e.idEstacion, 
					   e.estacionDesc 
				  FROM subproyectoestacion su,
					   estacion e,
					   estacionarea ea
				 WHERE e.idEstacion = ea.idEstacion
				   AND su.idEstacionArea = ea.idEstacionArea
				   AND idSubProyecto = " . $idSubProyecto . "
				 GROUP BY idEstacion";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan)
    {
        $Query = "SELECT    po.idEstadoPlan, po.indicador, sp.idProyecto, dp.itemPlan, dp.poCod,
                        	e.idEstacion, a.tipoArea, c.jefatura as jefatura_ptr, ec.empresaColabDesc as eecc,
                            ec.idEmpresacolab as idEecc, e.estacionDesc, po.idCentral, sp.idSubProyecto,
	                        c.region, c.tipoCentralDesc, c.codigo, po.paquetizado_fg
                        	FROM 	planobra po,
                        	subproyecto sp,
                        	detalleplan dp,
                        	subproyectoestacion se,
                        	estacionarea ea,
                        	estacion e,
                        	area a,
                        	central c,
                        	empresacolab ec
                	WHERE 	po.itemplan     = dp.itemplan
                	AND		po.idCentral 	= c.idCentral
                	AND		c.idEmpresacolab = ec.idEmpresaColab
                	AND		po.idSubProyecto = sp.idSubProyecto
                	AND 	dp.idSubProyectoEstacion 	= se.idSubProyectoEstacion
                	AND 	se.idEstacionArea 			= ea.idEstacionArea
                	AND 	ea.idEstacion	 = e.idEstacion
                	AND 	ea.idArea 		 = a.idArea
                    AND		dp.poCod 		 =  ?
                    AND 	dp.itemplan 	 =  ?
	                AND		po.paquetizado_fg is null
	                UNION ALL                     
                    SELECT    po.idEstadoPlan, po.indicador, sp.idProyecto, dp.itemPlan, dp.poCod,
                        	e.idEstacion, a.tipoArea, c.jefatura as jefatura_ptr, ec.empresaColabDesc as eecc,
                            ec.idEmpresacolab as idEecc, e.estacionDesc, po.idCentralPqt as idCentral, sp.idSubProyecto,
	                        c.region, c.tipoCentralDesc, c.codigo, po.paquetizado_fg
                        	FROM 	planobra po,
                        	subproyecto sp,
                        	detalleplan dp,
                        	subproyectoestacion se,
                        	estacionarea ea,
                        	estacion e,
                        	area a,
                        	pqt_central c,
                        	empresacolab ec
                	WHERE 	po.itemplan     = dp.itemplan
                	AND		po.idCentralPqt	= c.idCentral
                	AND		c.idEmpresacolab = ec.idEmpresaColab
                	AND		po.idSubProyecto = sp.idSubProyecto
                	AND 	dp.idSubProyectoEstacion 	= se.idSubProyectoEstacion
                	AND 	se.idEstacionArea 			= ea.idEstacionArea
                	AND 	ea.idEstacion	 = e.idEstacion
                	AND 	ea.idArea 		 = a.idArea
	                AND		dp.poCod 		 =  ?
                    AND 	dp.itemplan 	 =  ?
	                AND		po.paquetizado_fg IN (1,2)";
        $result = $this->db->query($Query, array($ptr, $itemplan, $ptr, $itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function getCountINConfigAutoProb($idsubProyecto)
    {
        $sql = "SELECT COUNT(capo.idSubProyecto) AS count
				  FROM config_autoaprob_po capo
				 WHERE capo.idSubProyecto = " . $idsubProyecto . " ";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function getFecPrevEjec($itemplan)
    {
        $sql = "SELECT po.fechaPrevEjec
				  FROM planobra po
				 WHERE po.itemplan = '" . $itemplan . "' ";
        $result = $this->db->query($sql);
        return $result->row_array()['fechaPrevEjec'];
    }

    public function insertarLOGTransWU($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_tranferencia_wu', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_tranferencia_wu');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getAllSubProyConfig()
    {
        $sql = "SELECT cap.idSubProyecto,
		               sp.subProyectoDesc,
					   sp.tiempo
		          FROM config_autoaprob_po cap, 
				       subproyecto sp 
				 WHERE cap.idSubProyecto = sp.idSubProyecto";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAllSubProySinConfigPO()
    {
        $sql = "SELECT sp.idSubProyecto,
		               sp.subProyectoDesc 
		          FROM subproyecto sp 
				 WHERE sp.idSubProyecto NOT IN (SELECT cap.idSubProyecto FROM config_autoaprob_po cap)";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getCountSubProyInConfigAutoAprob($arrayidsubProys)
    {
        $sql = "SELECT COUNT(capo.idSubProyecto) AS count
				  FROM config_autoaprob_po capo
				 WHERE capo.idSubProyecto IN ? ";
        $result = $this->db->query($sql, array($arrayidsubProys));
        return $result->row_array()['count'];
    }

    public function insertarSubProyConfigAutoAprobPO($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('config_autoaprob_po', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla config_autoaprob_po');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function deleteSubProyConfigAutoArpobPO($idSubProyecto)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idSubProyecto', $idSubProyecto);
            $this->db->delete('config_autoaprob_po');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente el SubProyecto!!.';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error transaccion DELETE config_autoaprob_po');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getAllMateriales()
    {
        $sql = "SELECT m.id_material,
		               m.descrip_material,
					   m.costo_material,
					   m.estado_material,
					   (CASE WHEN m.flg_tipo = '1' THEN 'BUCLE' ELSE 'NO BUCLE' END) AS tipo_material,
					   m.unidad_medida
		          FROM material m ";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function insertarMaterial($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('material', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla material');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getDetalleMaterial($codigoMaterial)
    {
        $sql = "SELECT m.id_material,
		               m.descrip_material,
                       m.costo_material,
                       UPPER(m.estado_material),
                       (CASE WHEN UPPER(m.estado_material) = 'ACTIVO'    THEN 1
                             WHEN UPPER(m.estado_material) = 'INACTIVO'  THEN 2
                             WHEN UPPER(m.estado_material) = 'PHASE OUT' THEN 3 
                             ELSE NULL END) AS flg_estado,
                       (CASE WHEN m.flg_tipo = '1' THEN 1 ELSE 0 END) AS flg_tipo,
                       UPPER(m.unidad_medida),
                       m.id_udm
		          FROM material m
				 WHERE m.id_material = '" . $codigoMaterial . "' ";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function getUDMMaterial()
    {
        $sql = "SELECT umm.id_udm,
                       umm.descrip_udm
                  FROM unidad_medida_material umm";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function updateMaterial($codigoMaterial, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('id_material', $codigoMaterial);
            $this->db->update('material', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el material.');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function simpleUpdatePlanObra($itemPlan, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->where('itemplan', $itemPlan);
            $this->db->update('planobra', $arrayData);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar el material.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    public function getIPParalizados()
    {
        $sql = "SELECT po.indicador,pa.itemplan
		          FROM paralizacion pa,
				       planobra po
                 WHERE pa.itemplan = po.itemPlan
				   AND pa.idMotivo = 42
		           AND pa.idUsuario = 265
		           AND pa.comentario = 'SIN CONFIGURACION PEP'
		           AND pa.flg_activo = 1
		           AND pa.fechaReactivacion IS NULL
		          -- AND DATE(pa.fechaRegistro) = CURDATE()
		          -- AND HOUR(pa.fechaRegistro) = HOUR(CURTIME())
		          -- AND MINUTE(pa.fechaRegistro) = MINUTE(CURTIME())
				  -- AND SECOND(pa.fechaRegistro) BETWEEN '0' AND '30'
				  ";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function insertBatchLogSigoplus($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('log_tramas_sigoplus', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el log_tramas_sigoplus.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getAllJefatura()
    {
        $sql = "SELECT j.idJefatura,
                       j.descripcion,
                       j.flgActivo
		          FROM jefatura j";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getCodigoPO($itemplan)
    {
        $Query = "SELECT getPoCod(?) as codigoPO";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['codigoPO'];
        } else {
            return null;
        }
    }

    function analizarSiropeDiseno($idUsuario)
    {
        $sql = "SELECT `fn_analizar_sirope_diseno`(265)";
        $result = $this->db->query($sql);
    }

    function getAllTipoCosto($idPrecioDiseno)
    {
        $sql = "SELECT idPrecioDiseno,
					   descPrecio,
					   flg_activo,
					   codigo_precio
				  FROM precio_diseno
				 WHERE flg_activo = 1 
				   AND idPrecioDiseno = COALESCE(?, idPrecioDiseno)";
        $result = $this->db->query($sql, array($idPrecioDiseno));
        return $result->result_array();
    }

    function getPrecioDisenoByItemplan($itemplan)
    {
        $sql = "SELECT DISTINCT 
						pd.idPrecioDiseno, 
						pd.descPrecio
				  FROM preciario pre,
						precio_diseno pd,
						planobra po,
						central c,
						partidas pa,
						subproyecto s,
						partida_subproyecto ps
				 WHERE pre.idPrecioDiseno = pd.idPrecioDiseno
					-- AND pa.idPrecioDiseno  = pre.idPrecioDiseno
					AND po.idCentral       = c.idCentral
					AND pre.idZonal        = c.idZonal
					AND pre.idEmpresaColab = po.idEmpresaColabDiseno
					AND ps.idSubProyecto   = s.idSubProyecto
					AND ps.idPartida       = pa.idActividad
					AND pd.flg_activo      = 1
					AND pa.flg_tipo        = 2
					AND po.itemplan        = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function validarComplejidadDiseno($itemplan)
    {
        $sql = "SELECT 1 AS flg
				  FROM subproyecto s,
						planobra po
				 WHERE s.idSubProyecto = po.idSubProyecto
				   AND s.idTipoComplejidad = " . ID_COMPLEJIDAD_MEDIA . "
				   AND po.itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['flg'];
    }

    function getIdProyectoByItemplan($itemplan)
    {
        $sql = "SELECT s.idProyecto
				  FROM subproyecto s,
						planobra po
				 WHERE s.idSubProyecto = po.idSubProyecto
				   AND po.itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['idProyecto'];
    }

    function insertFn_LogPo($itemplan, $po, $idUsuario, $idEstadoPo)
    {
        $sql = "SELECT insert_log_planobra_diseno('" . $itemplan . "', '" . $po . "', '" . $idUsuario . "', " . $idEstadoPo . ") AS flgValida";
        $result = $this->db->query($sql);
        return $result->row_array()['flgValida'];
    }

    function getECCbyidEmpresaSession($idEccSession, $idEccFiltro)
    {
        $sql = " SELECT * 
				   FROM empresacolab
			      WHERE idEmpresacolab <> 5
				    AND idEmpresacolab  = COALESCE(?, idEmpresacolab)
				    AND idEmpresacolab  = CASE WHEN " . $idEccSession . " = 0 or " . $idEccSession . "=6 THEN idEmpresacolab 
									   		   ELSE " . $idEccSession . " END";
        $result = $this->db->query($sql, array($idEccFiltro));
        return $result;
    }

    /*     * ************** czavala 11.02.2019************************** */

    function hasPtrMoActive($itemplan, $idEstacion)
    {
        $Query = "SELECT SUM(tb.cont) as total FROM (
	                   SELECT count(1) as cont FROM detalleplan dp, subproyectoestacion se, estacionarea ea, estacion e, area a, planobra_po  po
                    	where po.codigo_po = dp.poCod
                    	AND po.itemplan = dp.itemplan
                    	AND dp.itemplan = ?
                    	AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    	AND se.idEstacionArea = ea.idEstacionArea
                    	AND ea.idEstacion = e.idEstacion
                    	AND ea.idArea = a.idArea
                    	AND a.tipoArea = 'MO'
                    	AND e.idEstacion = ?
                    	AND po.estado_po NOT IN (7,8)
                    	UNION
                    	SELECT count(1) as cont FROM detalleplan dp, subproyectoestacion se, estacionarea ea, estacion e, area a, web_unificada wu
                    	where wu.ptr = dp.poCod
                    	AND dp.itemplan = ?
                    	AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    	AND se.idEstacionArea = ea.idEstacionArea
                    	AND ea.idEstacion = e.idEstacion
                    	AND ea.idArea = a.idArea
                    	AND a.tipoArea = 'MO'
                    	AND e.idEstacion = ?
                    	AND substring(wu.est_innova,1,2) != '04'
                    	AND substring(wu.est_innova,1,3) != '007') tb";
        $result = $this->db->query($Query, array($itemplan, $idEstacion, $itemplan, $idEstacion));
        if ($result->row() != null) {
            return $result->row_array()['total'];
        } else {
            return null;
        }
    }

    public function getProyectoByItemplan($itemplan)
    {
        $Query = "SELECT sp.idProyecto FROM planobra po, subproyecto sp
            	    where po.idSubProyecto = sp.idSubProyecto
            	    and po.itemplan = '$itemplan'";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['idProyecto'];
        } else {
            return null;
        }
    }

    public function getProyectoByItemplanAndFase($itemplan)
    {
        $Query = "SELECT sp.idProyecto, po.idFase FROM planobra po, subproyecto sp
            	    where po.idSubProyecto = sp.idSubProyecto
            	    and po.itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    public function getAllEntidades($idEntidad = null)
    {
        $sql = "  SELECT e.idEntidad,
                         e.desc_entidad,
                         DATE(e.fecha_registro) AS fecha_registro
                    FROM entidad e ";
        if ($idEntidad != null) {
            $sql .= " WHERE e.idEntidad = " . $idEntidad . "";
        }
        $result = $this->db->query($sql);
        return ($idEntidad == null ? $result->result() : $result->row_array());
    }

    public function countEntidad($descEntidad)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM entidad
				 WHERE desc_entidad = '" . $descEntidad . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarEntidad($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('entidad', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla entidad');
            } else {
                $idEntidadNew = $this->db->insert_id();
                $this->db->trans_commit();
                $data['idEntidadNew'] = $idEntidadNew;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogEntidad($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_entidad', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log entidad');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updateEntidad($idEntidad, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idEntidad', $idEntidad);
            $this->db->update('entidad', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la entidad!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function deleteEntidad($idEntidad)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idEntidad', $idEntidad);
            $this->db->delete('entidad');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente!!';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar la entidad!!');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function countEntidadInIPEstDet($idEntidad)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM itemplan_estacion_licencia_det
				 WHERE idEntidad = '" . $idEntidad . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function getPartidas($idActividad = null)
    {
        $sql = "  SELECT pa.idActividad,
                         pa.codigo,
                         pa.descripcion,
                         CAST(pa.baremo AS DECIMAL(65,2)) AS baremo,
                         pa.kit_material,
                         CAST(REPLACE(pa.costo_material,',','') AS DECIMAL(65,2)) AS costo_material,
                         pa.pliego,
                         pa.estado,
                         pa.idPrecioDiseno,
                         pd.descPrecio,
                         pd.codigo_precio,
                         pa.flg_tipo
					FROM partidas pa LEFT JOIN precio_diseno pd ON pa.idPrecioDiseno = pd.idPrecioDiseno
				   WHERE pa.idActividad = COALESCE(?, pa.idActividad)
				ORDER BY pa.codigo, pa.descripcion";
        // if ($idActividad != null) {
        //     $sql .= " WHERE pa.idActividad = " . $idActividad . "";
        // }
        $result = $this->db->query($sql, array($idActividad));
        return ($idActividad == null ? $result->result() : $result->row_array());
    }

    public function getAllPrecDiseno()
    {
        $sql = "SELECT pd.idPrecioDiseno,
                       pd.descPrecio,
                       pd.codigo_precio
                  FROM precio_diseno pd";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function insertarPartida($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('partidas', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log entidad');
            } else {
                $idActividadNew = $this->db->insert_id();
                $this->db->trans_commit();
                $data['idActividadNew'] = $idActividadNew;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function countPartida($codigo)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM partidas
				 WHERE codigo = '" . $codigo . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarLogPartida($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_partidas', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log partidas');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updatePartida($idActividad, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idActividad', $idActividad);
            $this->db->update('partidas', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la partida!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getInfoPoByCodigoPo($codigo_po)
    {
        $Query = "SELECT * FROM planobra_po WHERE codigo_po = ?";
        $result = $this->db->query($Query, array($codigo_po));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    public function getAllTipoComplejidad()
    {
        $sql = "SELECT tc.idTipoComplejidad,
                       tc.complejidadDesc
                  FROM tipo_complejidad tc";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEstacion($idEstacion = null)
    {
        $sql = "SELECT e.idEstacion,
				         e.estacionDesc
				FROM estacion e ";
        if ($idEstacion != null) {
            $sql .= " WHERE e.idEstacion = " . $idEstacion . "";
        }
        $result = $this->db->query($sql);
        return ($idEstacion == null ? $result->result() : $result->row_array());
    }

    public function getAllProyEstPartida($idProyecto = null, $idEstacion = null, $idPartida = null, $id = null, $flgResult = null)
    {
        $sql = "SELECT pa.codigo, pepm.id, pepm.idProyecto, p.proyectoDesc, pepm.idEstacion, e.estacionDesc, pepm.idPartida, pa.descripcion 
                 FROM proyecto_estacion_partida_mo pepm, 
                      proyecto p, 
                      estacion e, 
                      partidas pa 
                WHERE pepm.idProyecto = p.idProyecto 
                  AND pepm.idEstacion = e.idEstacion 
                  AND pepm.idPartida = pa.idActividad
                  AND p.idProyecto = COALESCE(?,p.idProyecto)
                  AND pa.idActividad = COALESCE(?,pa.idActividad) ";
        if ($idEstacion != null) {
            $sql .= " AND e.idEstacion IN (" . $idEstacion . ")";
        }
        if ($id != null) {
            $sql .= " AND pepm.id IN (" . $id . ")";
        }
        $result = $this->db->query($sql, array($idProyecto, $idPartida));
        return ($flgResult == null ? $result->result() : $result->row_array());
    }

    public function countProyEstPart($idProyecto, $idEstacion, $idPartida)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM proyecto_estacion_partida_mo
                 WHERE idProyecto = " . $idProyecto . "
                   AND idEstacion IN (" . $idEstacion . ")
                   AND idPartida = " . $idPartida . " ";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarProyEstPart($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('proyecto_estacion_partida_mo', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla proyecto_estacion_partida_mo');
            } else {
                $id = $this->db->insert_id();
                $this->db->trans_commit();
                $data['id'] = $id;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogProyEstPart($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('log_proyecto_estacion_partida_mo', $arrayInsert);
            if ($this->db->affected_rows() === false) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_proyecto_estacion_partida_mo');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updateProyEstPart($id, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('id', $id);
            $this->db->update('proyecto_estacion_partida_mo', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la proyecto_estacion_partida_mo!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function deleteProyEstPart($id)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('id', $id);
            $this->db->delete('proyecto_estacion_partida_mo');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente!!';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar!!');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getAllPreciario($idEmpresaColab = null, $idZonal = null, $idEstacion = null, $idPrecioDiseno = null, $flgResult = null)
    {
        $sql = "SELECT p.idEmpresaColab, ec.empresaColabDesc,p.idEstacion, e.estacionDesc, 
                       p.idPrecioDiseno, pd.descPrecio,p.idZonal,z.zonalDesc,p.costo
                 FROM preciario p, 
                      empresacolab ec, 
                      estacion e,
                      precio_diseno pd,
                      zonal z
                WHERE p.idEmpresaColab = ec.idEmpresaColab 
                  AND p.idEstacion = e.idEstacion 
                  AND p.idPrecioDiseno = pd.idPrecioDiseno
                  AND p.idZonal = z.idZonal
                  AND ec.idEmpresaColab = COALESCE(?,ec.idEmpresaColab)
                  AND z.idZonal = COALESCE(?,z.idZonal) 
                  AND pd.idPrecioDiseno = COALESCE(?,pd.idPrecioDiseno) ";
        if ($idEstacion != null) {
            $sql .= " AND p.idEstacion IN (" . $idEstacion . ")";
        }
        $result = $this->db->query($sql, array($idEmpresaColab, $idZonal, $idPrecioDiseno));
        return ($flgResult == null ? $result->result() : $result->row_array());
    }

    public function getAllPreciario2019($idEmpresaColab = null, $idZonal = null, $idEstacion = null, $idPrecioDiseno = null, $flgResult = null)
    {
        $sql = "SELECT p.idEmpresaColab, ec.empresaColabDesc,p.idEstacion, e.estacionDesc, 
                       p.idPrecioDiseno, pd.descPrecio,p.idZonal,z.zonalDesc,p.costo
                 FROM preciario_2019 p, 
                      empresacolab ec, 
                      estacion e,
                      precio_diseno pd,
                      zonal z
                WHERE p.idEmpresaColab = ec.idEmpresaColab 
                  AND p.idEstacion = e.idEstacion 
                  AND p.idPrecioDiseno = pd.idPrecioDiseno
                  AND p.idZonal = z.idZonal
                  AND ec.idEmpresaColab = COALESCE(?,ec.idEmpresaColab)
                  AND z.idZonal = COALESCE(?,z.idZonal) 
                  AND pd.idPrecioDiseno = COALESCE(?,pd.idPrecioDiseno) ";
        if ($idEstacion != null) {
            $sql .= " AND p.idEstacion IN (" . $idEstacion . ")";
        }
        $result = $this->db->query($sql, array($idEmpresaColab, $idZonal, $idPrecioDiseno));
        return ($flgResult == null ? $result->result() : $result->row_array());
    }

    public function countPreciario($idEECC, $idZonal, $idEstacion, $idTipoPrecio)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM preciario p
                 WHERE p.idEmpresaColab = " . $idEECC . "
                   AND p.idEstacion IN (" . $idEstacion . ")
                   AND p.idZonal = " . $idZonal . " 
                   AND p.idPrecioDiseno = " . $idTipoPrecio . " ";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarPreciario($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('preciario', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla preciario');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogPreciario($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('log_preciario', $arrayInsert);
            if ($this->db->affected_rows() === false) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_preciario');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updatePreciario($idTipoPrecio, $idZonal, $idEECC, $idEstacion, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idPrecioDiseno', $idTipoPrecio);
            $this->db->where('idZonal', $idZonal);
            $this->db->where('idEmpresaColab', $idEECC);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('preciario', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la preciario!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getEstacionByPartProy($idPartida, $idProyecto)
    {
        $sql = "SELECT e.idEstacion,
                       e.estacionDesc
                  FROM estacion e 
                 WHERE e.idEstacion NOT IN (  SELECT pepm.idEstacion 
                                                FROM proyecto_estacion_partida_mo pepm 
                                               WHERE pepm.idPartida = ?
                                                 AND pepm.idProyecto = ?
                                            ORDER BY pepm.idEstacion)";

        $result = $this->db->query($sql, array($idPartida, $idProyecto));
        return $result->result();
    }

    /*     * ******************CZAVALA NO BORRAR**************************** */

    function canValidPoCertificacion($idUsuario)
    {
        $query = " SELECT COUNT(1) as count
		          FROM usuarios_validadores where idUsuario = ?";
        $result = $this->db->query($query, array($idUsuario));
        return $result->row()->count;
    }

    public function getProyectoSubProyectoByItemplan($itemplan)
    {
        $Query = "SELECT
                    sp.idSubProyecto, sp.idProyecto
                FROM
                    planobra po,
                    subproyecto sp
                WHERE
                    po.idSubProyecto = sp.idSubProyecto
                AND po.itemplan = ?;";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    /*     * modificacion query getIdEstadoPlanCambio 20.06.2019 czavalacas** */

    function getIdEstadoPlanCambio($idSubProyecto)
    {
        $sql = "SELECT idEstadoPlan
				  FROM subproyecto_cambio_estado
				 WHERE flgActivo = 1
				   AND idSubProyecto = " . $idSubProyecto;
        $result = $this->db->query($sql);
        if ($result->row() != null) {
            return $result->row_array()['idEstadoPlan'];
        } else {
            return null;
        }
    }

    public function existPepInPepToro($pep)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM peptoro where id_pep = ?";
        $result = $this->db->query($sql, array($pep));
        return $result->row_array()['cantidad'];
    }

    public function insertarIPEstLicDet($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemplan_estacion_licencia_det', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla itemplan_estacion_licencia_det');
            } else {
                $id = $this->db->insert_id();
                $this->db->trans_commit();
                $data['iditemplan_estacion_licencia_det'] = $id;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getIPEstInsert($arrayIds)
    {
        $sql = "SELECT * FROM itemplan_estacion_licencia_det WHERE itemplan IN ? ";

        $result = $this->db->query($sql, array($arrayIds));
        return $result->result();
    }

    public function insertBatchReembolso($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('reembolso', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla reembolso');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    /*     * METODOS DE LICENCIAS CON CV INTEGRAL* */

    public function getEstacionesAnclasByItemplan($itemplan)
    {
        $sql = "SELECT DISTINCT(ea.idEstacion) 
                  FROM planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea
                 WHERE po.idSubProyecto = sp.idSubProyecto
                   AND sp.idSubProyecto = se.idSubProyecto
                   AND se.idEstacionArea = ea.idEstacionArea
                   AND po.itemplan = ?
                   AND ea.idEstacion IN (" . ID_ESTACION_COAXIAL . "," . ID_ESTACION_FO . ")";
        $result = $this->db->query($sql, array($itemplan));
        return $result->result();
    }

    public function insertLicenciasFromCV($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('itemplan_estacion_licencia_det', $arrayInsert);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla config_autoaprob_po');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function countItemplan($itemplan)
    {
        $sql = "SELECT COUNT(1)count
				  FROM planobra
				 WHERE itemplan = " . $itemplan . "";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    function generarPOLicenciaFinalizacion($idUsuario)
    {
        $sql = "SELECT fn_insertPOLicencia_finalizacion('" . $idUsuario . "') AS resp";
        $result = $this->db->query($sql);
        return $result->row_array()['resp'];
    }

    function generarPOLicenciaGestion($itemplan, $idUsuario, $idEstacion)
    {
        $sql = "SELECT fn_insertPOLicencia_gestion(?,?,?) AS resp";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $idUsuario));
        return $result->row_array()['resp'];
    }

    /*     * METODO PARA OBTENER CODIGO ALEATORIO DE CLUSTER SISEGO* */

    public function getCodCluster()
    {
        $sql = "  SELECT (CASE
								WHEN max(id_planobra_cluster)+1 < 10 THEN CONCAT('CL-',FLOOR(100 + RAND() * (999 - 100 + 1)),'00',max(id_planobra_cluster)+1)
								WHEN max(id_planobra_cluster)+1 < 100 THEN CONCAT('CL-',FLOOR(100 + RAND() * (999 - 100 + 1)),'0',max(id_planobra_cluster)+1)
								WHEN max(id_planobra_cluster)+1 < 1000 THEN CONCAT('CL-',FLOOR(100 + RAND() * (999 - 100 + 1)),max(id_planobra_cluster)+1)
								WHEN max(id_planobra_cluster)+1 < 10000 THEN CONCAT('CL-',FLOOR(10 + RAND() * (99 - 10 + 1)),max(id_planobra_cluster)+1)
								WHEN max(id_planobra_cluster)+1 < 100000 THEN CONCAT('CL-',FLOOR(1 + RAND() * (9 - 1 + 1)),max(id_planobra_cluster)+1)
							ELSE CONCAT('CL-',FLOOR(100000 + RAND() * (999999 - 100000 + 1))+1) END) as cod_cluster
					FROM planobra_cluster";
        $result = $this->db->query($sql);
        return $result->row_array()['cod_cluster'];
    }

    function inserHijos($array)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('planobra_cluster_hijos', $array);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
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

    function updateEstadoPOByItemplan($itemplan, $idEstadoPO)
    {
        $sql = "UPDATE planobra_po SET estado_po = " . $idEstadoPO . "
                    WHERE itemplan = '" . $itemplan . "'";
        $this->db->query($sql);

        if ($this->db->affected_rows() > 0) {
            return array(
                'error' => EXIT_ERROR,
                'msj' => 'error al actualizar'
            );
        } else {
            return array(
                'error' => EXIT_SUCCESS,
                'msj' => 'error al actualizar'
            );
        }
    }

    function getCountSiom($itemplan, $idEstacion)
    {
        $sql = "SELECT COUNT(1) AS count
				  FROM siom_obra
				 WHERE itemplan   = ? 
				   AND idEstacion = ?";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array()['count'];
    }

    function getCountSwitchSiom($idEECC, $jefatura, $idSubProyecto)
    {
        $sql = "SELECT  COUNT(1) AS cant
                FROM    switch_siom 
		        WHERE   idEmpresacolab = ? 
		        AND     jefatura = ?
		        AND     idsubProyecto = ? 
                AND     fecha <= now();";
        $result = $this->db->query($sql, array($idEECC, $jefatura, $idSubProyecto));
        return $result->row_array()['cant'];
    }

    public function countIPEstConcluido($itemplan, $idEstacion, $idEntidad)
    {
        $sql = "SELECT COUNT(1)count
				  FROM itemplan_estacion_licencia_det
                 WHERE itemplan = " . $itemplan . "
                   AND idEstacion = " . $idEstacion . "
                   AND idEntidad = " . $idEntidad . " 
                   AND flg_validado = '2' ";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    public function getJefaturaSapxEECC($idJefatura = null, $idEmpresaColab = null)
    {
        $sql = "  SELECT jse.idJefatura,
                         j.descripcion,
                         jse.idEmpresaColab,
                         ec.empresaColabDesc,
                         jse.codCentro,
                         jse.codAlmacen
                    FROM jefatura_sap_x_empresacolab jse,
                         jefatura_sap j,
                         empresacolab ec
                   WHERE jse.idJefatura = j.idJefatura
                     AND jse.idEmpresaColab = ec.idEmpresaColab";
        if ($idJefatura != null && $idEmpresaColab != null) {
            $sql .= " AND jse.idJefatura = " . $idJefatura . "
                      AND jse.idEmpresaColab = " . $idEmpresaColab . " ";
        }
        $sql .= " ORDER BY jse.codAlmacen";
        $result = $this->db->query($sql);
        return ($idJefatura == null && $idEmpresaColab == null ? $result->result() : $result->row_array());
    }

    public function getJefaturaTB()
    {
        $query = "SELECT *
                   FROM jefatura
               ORDER BY descripcion";
        $result = $this->db->query($query);
        return $result->result();
    }

    public function countAlmacenByJefaEECC($idJefatura, $idEECC)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM jefatura_sap_x_empresacolab
                 WHERE idJefatura = " . $idJefatura . "
                   AND idEmpresaColab = " . $idEECC . " ";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarAlmacen($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('jefatura_sap_x_empresacolab', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla jefatura_sap_x_empresacolab');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogAlmacen($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_jefatura_sap_x_empresacolab', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log log_jefatura_sap_x_empresacolab');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updateAlmacen($idJefatura, $idEECC, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idJefatura', $idJefatura);
            $this->db->where('idEmpresaColab', $idEECC);
            $this->db->update('jefatura_sap_x_empresacolab', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el almacen!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function countSubProyByDesc($subproyDesc)
    {
        $sql = "SELECT COUNT(1)count
				  FROM subproyecto
				 WHERE subProyectoDesc = TRIM(?)";
        $result = $this->db->query($sql, array($subproyDesc));
        return $result->row_array()['count'];
    }

    public function countSubGetIdProyByDesc($subproyDesc)
    {
        $sql = "SELECT COUNT(1) count, idSubProyecto
				  FROM subproyecto
				 WHERE subProyectoDesc = TRIM(?)";
        $result = $this->db->query($sql, array($subproyDesc));
        return $result->row_array();
    }

    public function getDistritosFromCentral()
    {
        $query = "SELECT distinct distrito from central";
        $result = $this->db->query($query);
        return $result->result();
    }

    public function getEECCfromDistritoCentral($distrito)
    {
        $query = "SELECT DISTINCT t.idEmpresaColab, t.empresaColabDesc, t.flgTipoSubProyecto
                    FROM ( 
                           SELECT DISTINCT ec.idEmpresaColab, ec.empresaColabDesc, 1 AS flgTipoSubProyecto
                             FROM central c, empresacolab ec
                            WHERE c.idEmpresaColab = ec.idEmpresaColab
                             -- AND c.flg_subproByNodoCV IS NULL
                              AND c.distrito = ?
                           UNION ALL
                           SELECT DISTINCT ec.idEmpresaColab, ec.empresaColabDesc, ec.flg_trabajo AS flgTipoSubProyecto
                            FROM central c, empresacolab ec
                            WHERE c.idEmpresaColabCV = ec.idEmpresaColab
                              AND c.distrito = ?)t";
        $result = $this->db->query($query, array($distrito, $distrito));
        return $result->result();
    }

    public function getEECCfromDistritoCentralPqt($distrito)
    {
        $query = "SELECT DISTINCT t.idEmpresaColab, t.empresaColabDesc, t.flgTipoSubProyecto
                    FROM ( 
                           SELECT DISTINCT ec.idEmpresaColab, ec.empresaColabDesc, 1 AS flgTipoSubProyecto
                             FROM pqt_central c, empresacolab ec
                            WHERE c.idEmpresaColab = ec.idEmpresaColab
                             -- AND c.flg_subproByNodoCV IS NULL
                              AND c.distrito = ?
                           UNION ALL
                           SELECT DISTINCT ec.idEmpresaColab, ec.empresaColabDesc, ec.flg_trabajo AS flgTipoSubProyecto
                            FROM pqt_central c, empresacolab ec
                            WHERE c.idEmpresaColabCV = ec.idEmpresaColab
                              AND c.distrito = ?)t";
        $result = $this->db->query($query, array($distrito, $distrito));
        return $result->result();
    }

    function getInfocentralByDistritoAndEECC($distrito, $eecc)
    {
        $Query = "SELECT * 
                    FROM central 
                   WHERE distrito = ? 
                     AND idEmpresaColab = ? 
                    -- AND flg_subproByNodoCV IS NULL
                    LIMIT 1";
        $result = $this->db->query($Query, array($distrito, $eecc));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getInfocentralByDistritoAndEECCPqt($distrito, $eecc)
    {
        $Query = "SELECT * 
                    FROM pqt_central 
                   WHERE distrito = ? 
                     AND idEmpresaColab = ? 
                    -- AND flg_subproByNodoCV IS NULL
                    LIMIT 1";
        $result = $this->db->query($Query, array($distrito, $eecc));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getInfocentralByDistritoAndEECCIntegral($distrito, $eecc)
    {
        $Query = "SELECT * 
                    FROM central 
                   WHERE distrito = ? 
                     AND idEmpresaColabCv = ? 
                    -- AND flg_subproByNodoCV IS NOT NULL
                    LIMIT 1";
        $result = $this->db->query($Query, array($distrito, $eecc));

        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getDataProcesoPiloto($itemplan)
    {
        $sql = "SELECT  p.itemplan,
		                comentario_asig_facil,
						fecha_registro_asig_facil,
						DATE(fecha_cita_agen_replanteo) AS fecha_cita_agen_replanteo,
						comentario_agen_replanteo,
						comentario_replanteo,
						comentario_elaboracion_fuit,
						ubic_archivo_elaboracion_fuit,
						comentario_entrega_fuit,
						DATE(fecha_cita_agen_instalacion) AS fecha_cita_agen_instalacion,
						comentario_agen_instalacion,
						comentario_instalacion_pex,
						(SELECT co.orden 
						   FROM config co
						  WHERE co.id_config = p.id_config)orden,
					    (SELECT placa 
						   FROM planobra_x_auto
						  WHERE estado = 1
							AND itemplan = p.itemplan
							limit 1) AS placa,
						(SELECT COUNT(1) countMotivoEjec 
						   FROM proceso_piloto_x_motivo
						  WHERE idMotivo = " . ID_MOTIVO_EJECUCION_EN_PROCESO . "
                            AND itemplan = p.itemplan)countMotivoEjec,
                        po.fecha_creacion,
						p.fecha_registro_asig_facil,
						p.fecha_reg_replanteo,
						p.fecha_reg_elaboracion_fuit,
                        p.fecha_reg_entrega_fuit,
                        CASE WHEN p.duracion_asig_facil IS NOT NULL THEN p.duracion_asig_facil
							 ELSE TIMEDIFF(NOW(),po.fecha_creacion) END duracionFluidUno,
						CASE WHEN p.duracion_replanteo IS NOT NULL THEN p.duracion_replanteo
							 ELSE TIMEDIFF(NOW(),p.fecha_registro_asig_facil) END duracionFluidTres,
						CASE WHEN p.duracion_elaboracion_fuit IS NOT NULL THEN p.duracion_elaboracion_fuit
							 ELSE TIMEDIFF(NOW(),p.fecha_reg_replanteo) END duracionFluidCuatro,
						CASE WHEN p.duracion_entrega_fuit IS NOT NULL THEN p.duracion_entrega_fuit
							 ELSE TIMEDIFF(NOW(),p.fecha_reg_elaboracion_fuit) END duracionFluidCinco,
						CASE WHEN p.duracion_instalacion_pex IS NOT NULL THEN p.duracion_instalacion_pex
						     ELSE TIMEDIFF(NOW(),p.fecha_reg_entrega_fuit) END duracionFluidSiete		 		 		 		  
                  FROM planobra po LEFT JOIN proceso_piloto p ON (po.itemplan = p.itemplan)  
                 WHERE po.itemplan  = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    function getAllAutoByItemplan($itemplan = NULL)
    {
        $sql = "SELECT a.placa,
					   a.marca,
					   a.modelo,
					   a.estado,
					   CASE WHEN pxa.itemplan IS NOT NULL THEN 1 ELSE 0 END flgPlaca
				  FROM auto a LEFT JOIN planobra_x_auto pxa ON(a.placa = pxa.placa 
				                                              AND pxa.itemplan = COALESCE(?, pxa.itemplan)  
															  AND pxa.estado   = " . ESTADO_AUTO_OBRA_ACTIVO . ")
				   AND a.estado = " . ESTADO_AUTO_ACTIVO;
        $result = $this->db->query($sql, array($itemplan));
        return $result->result_array();
    }

    function getMaterialAuto($itemplan, $placa)
    {
        $sql = "SELECT m.id_material,
					   mxa.placa,
					   mxa.cantidad_kit,
					   m.descrip_material,
					   m.costo_material,
					   k.cantidad_final,
					   ROUND(m.costo_material*k.cantidad_final,2) AS costoMat
				  FROM (material m,
				       material_x_auto mxa)  
			 LEFT JOIN kit_material_auto k ON(k.id_material = m.id_material AND k.placa = ? 
				   							  AND mxa.placa = k.placa)
				 WHERE m.flg_auto= 1
				   AND mxa.id_material = m.id_material";
        $result = $this->db->query($sql, array($placa));
        return $result->result_array();
    }

    public function getPlacas($placa = null)
    {
        $sql = "  SELECT au.placa,
                         au.marca,
						 au.modelo,
						 au.estado
					FROM auto au
				   WHERE au.placa = COALESCE(?, au.placa)
				ORDER BY au.placa ";
        $result = $this->db->query($sql, array($placa));
        return ($placa == null ? $result->result() : $result->row_array());
    }

    public function countPlaca($placa)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM auto
				 WHERE placa = '" . $placa . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarPlaca($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('auto', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla auto');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogPlaca($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_placas', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log placas');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updatePlaca($placa, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('placa', $placa);
            $this->db->update('auto', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el auto!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getNewAllJefatura()
    {
        $sql = "SELECT idJefatura,
					   descripcion 
				  FROM jefatura
				 WHERE flgActivo = 1";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function getPlacasxVR($codigoVR = null, $placa = null)
    {
        $sql = "  SELECT va.placa,
                         va.codigo_vale_reserva
					FROM vale_reserva_x_auto va
				   WHERE va.codigo_vale_reserva = COALESCE(?, va.codigo_vale_reserva)
				     AND va.placa = COALESCE(?, va.placa)
				ORDER BY va.placa ";
        $result = $this->db->query($sql, array($codigoVR, $placa));
        return ($codigoVR == null ? $result->result() : $result->row_array());
    }

    public function countPlacaxVR($codigoVR, $placa)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM vale_reserva_x_auto va
				 WHERE va.placa = '" . $placa . "'
				   AND va.codigo_vale_reserva = '" . $codigoVR . "' ";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarPlacaxVR($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('vale_reserva_x_auto', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla vale_reserva_x_auto');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogPlacaxVR($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_placa_x_vale_reserva', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_placa_x_vale_reserva');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updatePlacaxVR($codigoVR, $placa, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('codigo_vale_reserva', $codigoVR);
            $this->db->where('placa', $placa);
            $this->db->update('vale_reserva_x_auto', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el vale_reserva_x_auto!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getSubProyByProyRegIP($idProyecto, $flgRegitroCableadoEdif)
    {
        $sql = "  SELECT *
	                FROM subproyecto
	                WHERE idProyecto = ?
					  AND CASE WHEN ? = 1 AND idProyecto = 21 THEN idSubProyecto NOT IN (96,97,98,99)
					           ELSE TRUE END
					  AND CASE WHEN idProyecto = 3 THEN idSubProyecto IN (82,446) ELSE TRUE END
	                  AND SUBSTRING_INDEX( subproyectoDesc , ' ', 1 ) NOT IN(2016,2017)
	                ORDER BY subProyectoDesc";
        $result = $this->db->query($sql, array($idProyecto, $flgRegitroCableadoEdif));
        return $result;
    }

    //USADO PARA EL REGISTRO DE ITEMPLAN, SOLO SUBPROYECTOS CON EL INDICADOR QUE PERTENECE AL MODULO PAQUETIZADO
    public function getSubProyByProyRegIPSoloPqt($idProyecto, $flgRegitroCableadoEdif)
    {
        $sql = "  SELECT *
	                FROM subproyecto
	                WHERE idProyecto = ?
					  AND CASE WHEN ? = 1 AND idProyecto = 21 THEN idSubProyecto NOT IN (96,97,98,99)
					           ELSE TRUE END
					  AND CASE WHEN idProyecto = 3 THEN idSubProyecto IN (82,446) ELSE TRUE END
	                  AND SUBSTRING_INDEX( subproyectoDesc , ' ', 1 ) NOT IN(2016,2017)
					  AND paquetizado_fg IN (1,2)
					  AND estado = 1
	                 ORDER BY subProyectoDesc";
        $result = $this->db->query($sql, array($idProyecto, $flgRegitroCableadoEdif));
        return $result;
    }

    public function insertarLogCentral($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_central', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_central');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function countJefatura($jefatura)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM jefatura
				 WHERE descripcion = '" . $jefatura . "'";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarJefatura($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('jefatura', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla jefatura');
            } else {
                $id = $this->db->insert_id();
                $this->db->trans_commit();
                $data['idJefatura'] = $id;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogJefatura($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_jefatura', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_jefatura');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getDetJefatura($idJefatura)
    {
        $sql = "  SELECT j.idJefatura,
                         j.descripcion,
                         j.flgActivo
				    FROM jefatura j
				   WHERE j.idJefatura = COALESCE(?, j.idJefatura)
				ORDER BY j.descripcion ";
        $result = $this->db->query($sql, array($idJefatura));
        return $result->row_array();
    }

    public function updateJefatura($idJefatura, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idJefatura', $idJefatura);
            $this->db->update('jefatura', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el jefatura!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getPartidaSubProy($idPartida = null, $idSubProyecto = null, $flgConsulta = null)
    {
        $sql = "  SELECT 
                        asp.idactividad_x_subProyecto,
                        api.idActividad,
                        asp.idSubProyecto,
                        api.descripcion as actividad,
                        api.baremo,
                        api.kit_material,
                        api.costo_material,
                        api.pliego,
                        sp.subProyectoDesc,
                        api.estado,
						api.*
                    FROM actividad_x_subproyecto asp,
                         subproyecto sp,
                         partidas api
                   WHERE asp.idActividad = api.idActividad 
                     AND asp.idSubProyecto = sp.idSubProyecto
                     AND api.flg_tipo      = 1
					 AND api.idActividad = COALESCE(?, api.idActividad)
                     AND asp.idSubProyecto = COALESCE(?, asp.idSubProyecto)";
        $result = $this->db->query($sql, array($idPartida, $idSubProyecto));
        return ($flgConsulta == null ? $result->result() : $result->row_array());
    }

    public function getSubProyByPartida($idPartida, $idTipoPlanta = null)
    {
        $sql = "SELECT sp.idSubProyecto,
                       sp.subProyectoDesc
                  FROM subproyecto sp
                 WHERE sp.idSubProyecto NOT IN (  SELECT ps.idSubProyecto 
                                                    FROM actividad_x_subproyecto ps 
                                                   WHERE ps.idActividad = ?
                                                ORDER BY ps.idSubProyecto )
				   AND sp.idTipoPlanta = COALESCE(?, sp.idTipoPlanta)";

        $result = $this->db->query($sql, array($idPartida, $idTipoPlanta));
        return $result;
    }

    public function countPartSubProy($idPartida, $idSubProyecto)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM partida_subproyecto ps
                 WHERE ps.idPartida = " . $idPartida . "
                   AND ps.idSubProyecto IN (" . $idSubProyecto . ")  ";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function countPartSubProyPin($idPartida, $idSubProyecto)
    {
        $sql = "SELECT COUNT(1) AS cantidad
				  FROM actividad_x_subproyecto ps
                 WHERE ps.idActividad = " . $idPartida . "
                   AND ps.idSubProyecto IN (" . $idSubProyecto . ")  ";
        $result = $this->db->query($sql);
        return $result->row_array()['cantidad'];
    }

    public function insertarPartSubProy($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('partida_subproyecto', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla partida_subproyecto');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarPartSubProyPin($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('actividad_x_subproyecto', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla partida_subproyecto');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    public function insertarLogPartSubProy($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('log_partida_subproyecto', $arrayInsert);
            if ($this->db->affected_rows() === false) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_partida_subproyecto');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function countPoExist($itemplan, $idEstacion, $idArea)
    {
        $sql = "SELECT COUNT(1) count
                  FROM detalleplan dp INNER JOIN
                       planobra_po po ON(dp.poCod = po.codigo_po)
                 WHERE dp.itemplan   = ?
                   AND dp.idSubProyectoEstacion IN (  SELECT idSubProyectoEstacion 
                                                       FROM subproyectoestacion 
                                                      WHERE idSubProyecto = ( SELECT idSubProyecto 
                                                                                FROM planobra 
                                                                               WHERE itemplan = ?) 
                                                        AND idEstacionArea IN (
                                                                                SELECT idEstacionArea 
                                                                                  FROM estacionarea 
                                                                                 WHERE idEstacion = ?
                                                                                   AND idArea     = ? 
                                                                            ) 
                                                   )
                   AND po.estado_po <> 8
                limit 1";
        $result = $this->db->query($sql, array($itemplan, $itemplan, $idEstacion, $idArea));
        return $result->row_array()['count'];
    }

    public function updatePartSubProy($idPartida, $idSubProyecto, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idPartida', $idPartida);
            $this->db->where('idSubProyecto', $idSubProyecto);
            $this->db->update('partida_subproyecto', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la tabla partida_subproyecto!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getPlanobraByItemplan($itemplan)
    {
        $sql = "SELECT *
	              FROM planobra 
	             WHERE itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    public function registrarCotIndividual($arrayData)
    {
        $this->db->insert('planobra_cluster', $arrayData);

        if ($this->db->affected_rows() != 1) {
            return 0;
        } else {
            return 1;
        }
    }

    function getTipoDiseno($idTipoDiseno = null)
    {
        $sql = "SELECT id_tipo_diseno,
                       descripcion
                  FROM tipo_diseno
                 WHERE id_tipo_diseno = COALESCE(?, id_tipo_diseno)
                   AND flg_activo = 1";
        $result = $this->db->query($sql, array($idTipoDiseno));
        return $result->result_array();
    }

    function existVROnAprobacion($vale_reserva)
    {
        $sql = "SELECT SUM(tb.num) as has_vr 
	               FROM (
                    	SELECT count(1) as num from planobra_po where vale_reserva = TRIM(?)
                    	UNION ALL
                    	SELECT count(1) as num from web_unificada_det where vale_reserva = TRIM(?)
	               ) as tb group by tb.num;";
        $result = $this->db->query($sql, array($vale_reserva, $vale_reserva));
        return $result->row_array()['has_vr'];
    }

    public function sendDataToURLTypePUT($url, $dataSend)
    {

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dataSend);
            // OPTIONS:
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $response = curl_exec($curl);
            curl_close($curl);
        } catch (Exception $e) {
            //insert log
            _log('catch sendDataToURLTypePUT');
        }

        return json_decode($response);
    }

    public function getAllTipoSubProyecto()
    {
        $sql = "  SELECT ts.id_tipo_subproyecto,
                         ts.descripcion
                    FROM tipo_subproyecto ts
                ORDER BY ts.descripcion ";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getSubProyectoById($arrayData, $flg = null, $itemplan = null)
    {
        $sql = "SELECT t.*,
                       CASE WHEN tt.subProyectoTres IS NOT NULL AND ? 
                            IS NOT NULL THEN 1 END flgSubSelected 
                  FROM (
                      SELECT idSubProyecto,
                             subProyectoDesc,
                             SUBSTRING_INDEX(subProyectoDesc,'-',1) subproyectoDescDos,
                             idTipoComplejidad,
                             idTipoSubProyecto
                        FROM subproyecto
                       WHERE flg_tipo_cv IN (" . implode(',', $arrayData) . ")
                        )t LEFT JOIN (SELECT SUBSTRING_INDEX(s.subProyectoDesc,'-',1) subProyectoTres
                                        FROM planobra po, 
                                             subproyecto s 
                                       WHERE s.idSubProyecto = po.idSubProyecto
                                         AND po.itemplan = ?)tt ON (tt.subProyectoTres = t.subproyectoDescDos)
                GROUP BY t.subproyectoDescDos";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        if ($flg == 1) {
            return $result->row_array();
        } else {
            return $result->result_array();
        }
    }

    function getInfoToSwitchSiomByItemplan($itemplan)
    {
        $sql = "SELECT po.idSubProyecto, c.jefatura, (CASE WHEN s.idTipoSubProyecto = 2  THEN c.idEmpresaColabCV
            	ELSE c.idEmpresaColab END) as idEmpresaColab
            	from planobra po, central c, subproyecto s
               where po.idCentral = c.idCentral
                 and s.idSubProyecto = po.idSubProyecto
            	and po.itemplan = ?
            AND po.paquetizado_fg is null
            UNION ALL
            SELECT po.idSubProyecto, c.jefatura, (CASE WHEN s.idTipoSubProyecto = 2  THEN c.idEmpresaColabCV
            	ELSE c.idEmpresaColab END) as idEmpresaColab
            	from planobra po, pqt_central c, subproyecto s
               where po.idCentralPqt = c.idCentral
                 and s.idSubProyecto = po.idSubProyecto
            	and po.itemplan = ?
            AND po.paquetizado_fg in (1,2)";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->row_array();
    }

    function getArrayFase()
    {
        $sql = "SELECT * 
                  FROM fase f LEFT JOIN (SELECT EXTRACT(YEAR FROM NOW())as anio)t ON (f.faseDesc = t.anio)";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getSiomDataFromIdSiom($itemplan)
    {
        $Query = "SELECT * FROM siom_obra WHERE id_siom_obra = ?;";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getComplejidad($itemplan)
    {
        $sql = "SELECT t.idTipoComplejidad,
                       t.complejidadDesc,
                       CASE WHEN s.idSubProyecto IS NOT NULL THEN 1 
                            ELSE NULL END flgSelected 
                  FROM tipo_complejidad t 
             LEFT JOIN (subproyecto      s,
                        planobra        po) ON (t.idTipoComplejidad = s.idTipoComplejidad 
                                               AND s.idSubProyecto  = po.idSubProyecto
                                               AND po.itemplan      = ?)
                 WHERE t.idTipoComplejidad <> 3                          
                HAVING flgSelected IS NULL";
        $result = $this->db->query($sql, array($itemplan));
        return $result->result_array();
    }

    function getComplejidadByCodigoPo($codigo_po)
    {
        $sql = "SELECT DISTINCT
					   t.idTipoComplejidad,
					   t.complejidadDesc,
					   CASE WHEN pa.idActividad IS NOT NULL THEN 1 
							ELSE NULL END flgSelected  
				  FROM tipo_complejidad t 
			 LEFT JOIN partidas pa
				    ON t.idTipoComplejidad = pa.idTipoComplejidad
				   AND pa.idActividad IN (	SELECT dp.idPartida
											  FROM planobra_po_detalle_partida dp
											 WHERE dp.codigo_po = ?)
				WHERE t.idTipoComplejidad <> 3                              
				HAVING flgSelected IS NULL";
        $result = $this->db->query($sql, array($codigo_po));
        return $result->result_array();
    }

    function getEstacionByItemplanCmb($itemplan, $idEstacion, $flgCambioPo)
    {
        $sql = "    SELECT su.idSubProyecto,
                            ea.idArea,
                            ea.idEstacion,
                            e.estacionDesc,
                            ppo.codigo_po
                      FROM (subproyectoestacion su,
                            estacionarea ea,
                            estacion e,
                            planobra po) 
                 LEFT JOIN planobra_po ppo ON (po.itemplan = ppo.itemplan AND ppo.idEstacion    = ea.idEstacion)
                     WHERE su.idEstacionArea = ea.idEstacionArea
                       AND su.idSubProyecto  = po.idSubProyecto
                       AND e.idEstacion      = ea.idEstacion
                       AND po.itemplan       = COALESCE(?, po.itemplan)
                       AND ea.idEstacion     = COALESCE(?, ea.idEstacion)
                       AND CASE WHEN ? IS NOT NULL THEN ea.idEstacion IN(2,5) ELSE TRUE END -- SI ES CAMBIO DE PO COPLEJIDAD NO APAREZCA LA ESTACION DISENO 
                     GROUP BY e.idEstacion";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $flgCambioPo));
        return $result->result_array();
    }

    function getCodigoPoByEstacionItemplan($itemplan, $idEstacion, $flgRow = null)
    {
        $sql = "SELECT ppo.codigo_po
                  FROM planobra_po ppo
                 WHERE ppo.idEstacion = COALESCE(?, ppo.idEstacion)
                   AND ppo.itemplan   = COALESCE(?, ppo.itemplan)";
        $result = $this->db->query($sql, array($idEstacion, $itemplan));
        if ($flgRow == 1) {
            return $result->row_array()['codigo_po'];
        } else {
            return $result->result_array();
        }
    }

    /**
     * Metodo para forzar el precio de FO de la partida 23108-8, pedido de owen dia 05.06.2019
     */
    function getCostoFoPartidasByItemplan($itemplan)
    {
        $sql = "SELECT pr.* FROM planobra po, central c, preciario pr
                where po.idCentral = c.idCentral and c.idZonal = pr.idZonal
                and c.idEmpresaColab = pr.idEmpresaColab and pr.idPrecioDiseno = 3 
                and idEstacion = " . ID_ESTACION_FO . "
                and itemplan = ? LIMIT 1;";
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getInfoItemplanSisegoPlanObra($itemplan)
    {
        $Query = "SELECT    count(1) as cant 
                    FROM    sisego_planobra sp
                    WHERE   sp.itemplan     = ?";

        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['cant'];
        } else {
            return null;
        }
    }

    function getDataCotizacionIndividual($sisego, $codigo, $flgDetalle = null, $idSubProyecto = null, $estado = null, $idJefatura = null, $idEmpresaColab = null, $flgBandConf = null, $itemplan = null)
    {
        $ideecc  = $this->session->userdata("eeccSession");
        $sql = "SELECT DISTINCT
		                pc.codigo_cluster, 
                        pc.itemplan,
                        pc.sisego,
                        pc.cliente, 
                        (SELECT concat(codigo,'-',tipoCentralDesc) 
                           FROM central c
                          WHERE c.idCentral = pc.nodo_principal) AS nodo_principal,
                        (SELECT concat(codigo,'-',tipoCentralDesc) 
                           FROM central c
                          WHERE c.idCentral = pc.nodo_respaldo) AS nodo_respaldo,
                        pc.facilidades_de_red,
                        pc.cant_cto,
						pc.longitud,
                        pc.latitud,
						pc.clasificacion,	 
						CASE WHEN pc.flg_lan_to_lan = 1 THEN pc.nombre_estudio 
						     WHEN pc.flg_principal  = 0 THEN 'PRINCIPAL'
                             WHEN pc.flg_principal  = 1 THEN 'RESPALDO' END flg_principal,
						CASE WHEN pc.flg_robot = 1 THEN 'ROBOT'
                             WHEN pc.flg_robot = 2 THEN 'EECC' END hizo_coti,						
                        pc.metro_tendido_aereo,
						ce.codigo,
                        pc.metro_tendido_subterraneo,
                        pc.metors_canalizacion,
                        pc.cant_camaras_nuevas,
                        pc.cant_postes_nuevos,
                        pc.cant_postes_apoyo,
                        pc.cant_apertura_camara,
                        pc.requiere_seia,
                        pc.requiere_aprob_mml_mtc,
                        pc.requiere_aprob_inc,
                        pc.duracion,
                        UPPER(t.descripcion) AS tipo_diseno_desc,
                        ROUND(COALESCE(pc.costo_materiales, 0)+ COALESCE(pc.costo_mat_edif, 0), 2) as costo_materiales,
                        ROUND(COALESCE(pc.costo_mano_obra, 0)+ COALESCE(pc.costo_mo_edif, 0) + COALESCE(pc.costo_oc, 0) + COALESCE(pc.costo_oc_edif, 0), 2) costo_mano_obra,
                        pc.costo_diseno,
                        pc.costo_expe_seia_cira_pam,
                        pc.costo_adicional_rural,
                        pc.costo_total,
                        pc.ubic_perfil,
                        pc.ubic_sisego,
                        pc.ubic_rutas,
                        pc.fecha_registro,
                        fecha_envio_cotizacion,
                        po.operador,
                        pc.acceso_cliente,
                        pc.nombre_estudio,
                        pc.tendido_externo,
                        pc.tipo_cliente,
                        pc.departamento,
                        pc.segmento,
                        pc.ubic_perfil,
                        pc.ubic_sisego,
                        pc.ubic_rutas,
                        pc.tipo_requerimiento,
                        pc.tipo_enlace,
                        (SELECT u.nombre 
                           FROM usuario u
                          WHERE u.id_usuario = pc.usuario_envio_cotizacion)AS nombreUsuarioEnvioCoti,
                        CASE WHEN pc.estado  = 0 THEN 'PDT COTIZACION'
                             WHEN pc.estado  = 1 THEN 'PDT APROBACION'
                             WHEN pc.estado  = 2 THEN 'APROBADO'
                             WHEN pc.estado  = 3 THEN 'RECHAZADO' 
                             WHEN pc.estado  = 4 THEN 'PDT CONFIRMACION' END estado,
                        UPPER(pc.comentario) AS comentario,
						(SELECT UPPER(nom_estacion) 
						   FROM ebc_ubicacion e
						  WHERE e.codigo = pc.facilidades_de_red limit 1) nom_ebc
                    FROM (planobra_cluster pc,
                        subproyecto s,
                        empresacolab e)
			   LEFT JOIN central ce ON (ce.idCentral = pc.idCentral)  		
               LEFT JOIN tipo_diseno t ON (pc.id_tipo_diseno = t.id_tipo_diseno) 
              LEFT JOIN planobra po
                     ON (pc.itemplan      = po.itemplan)
                  WHERE pc.flg_tipo       = 2 -- REGISTRO INDIVIDUAL
                    AND CASE WHEN pc.sisego IS NOT NULL THEN pc.sisego = COALESCE(?, pc.sisego)
                             ELSE true END
                    AND s.idSubProyecto   = pc.idSubProyecto       
                    AND pc.codigo_cluster = COALESCE(?, pc.codigo_cluster)
                    AND pc.idSubProyecto  = COALESCE(?, pc.idSubProyecto)
                    AND pc.estado         = COALESCE(?, pc.estado)
					AND CASE WHEN ? = '' OR ? IS NULL THEN true ELSE pc.itemplan = ? END
					AND pc.flg_paquetizado IS NULL
					AND pc.idEmpresaColab = e.idEmpresaColab
					AND CASE WHEN ce.idCentral IS NOT NULL THEN ce.idJefatura = COALESCE(?, ce.idJefatura)
							 ELSE true END
					AND pc.idEmpresaColab = COALESCE(?, e.idEmpresaColab)
                    AND CASE WHEN ? IS NOT NULL THEN pc.flg_rech_conf_ban_conf = ?
                             ELSE TRUE END
					AND CASE WHEN " . $ideecc . " = 0 OR " . $ideecc . " = 6 THEN true
                             ELSE pc.idEmpresaColab = " . $ideecc . " END
				UNION ALL  
				  SELECT DISTINCT
		                pc.codigo_cluster, 
                        pc.itemplan,
                        pc.sisego,
                        pc.cliente, 
                        (SELECT concat(codigo,'-',tipoCentralDesc) 
                           FROM pqt_central c
                          WHERE c.idCentral = pc.nodo_principal) AS nodo_principal,
                        (SELECT concat(codigo,'-',tipoCentralDesc) 
                           FROM pqt_central c
                          WHERE c.idCentral = pc.nodo_respaldo) AS nodo_respaldo,
                        pc.facilidades_de_red,
                        pc.cant_cto,
						pc.longitud,
                        pc.latitud,
						pc.clasificacion,	 
						CASE WHEN pc.flg_lan_to_lan = 1 THEN pc.nombre_estudio 
						     WHEN pc.flg_principal  = 0 THEN 'PRINCIPAL'
                             WHEN pc.flg_principal  = 1 THEN 'RESPALDO' END flg_principal,
						CASE WHEN pc.flg_robot = 1 THEN 'ROBOT'
                             WHEN pc.flg_robot = 2 THEN 'EECC' END hizo_coti,						
                        pc.metro_tendido_aereo,
						ce.codigo,
                        pc.metro_tendido_subterraneo,
                        pc.metors_canalizacion,
                        pc.cant_camaras_nuevas,
                        pc.cant_postes_nuevos,
                        pc.cant_postes_apoyo,
                        pc.cant_apertura_camara,
                        pc.requiere_seia,
                        pc.requiere_aprob_mml_mtc,
                        pc.requiere_aprob_inc,
                        pc.duracion,
                        UPPER(t.descripcion) AS tipo_diseno_desc,
                        COALESCE(pc.costo_materiales, 0)+ COALESCE(pc.costo_mat_edif, 0) as costo_materiales,
                        COALESCE(pc.costo_mano_obra, 0)+ COALESCE(pc.costo_mo_edif, 0) +  COALESCE(pc.costo_oc, 0)+ COALESCE(pc.costo_oc_edif, 0) costo_mano_obra,
                        pc.costo_diseno,
                        pc.costo_expe_seia_cira_pam,
                        pc.costo_adicional_rural,
                        pc.costo_total,
                        pc.ubic_perfil,
                        pc.ubic_sisego,
                        pc.ubic_rutas,
                        pc.fecha_registro,
                        fecha_envio_cotizacion,
                        po.operador,
                        pc.acceso_cliente,
                        pc.nombre_estudio,
                        pc.tendido_externo,
                        pc.tipo_cliente,
                        pc.departamento,
                        pc.segmento,
                        pc.ubic_perfil,
                        pc.ubic_sisego,
                        pc.ubic_rutas,
                        pc.tipo_requerimiento,
                        pc.tipo_enlace,
                        (SELECT u.nombre 
                           FROM usuario u
                          WHERE u.id_usuario = pc.usuario_envio_cotizacion)AS nombreUsuarioEnvioCoti,
                        CASE WHEN pc.estado  = 0 THEN 'PDT COTIZACION'
                             WHEN pc.estado  = 1 THEN 'PDT APROBACION'
                             WHEN pc.estado  = 2 THEN 'APROBADO'
                             WHEN pc.estado  = 3 THEN 'RECHAZADO' 
                             WHEN pc.estado  = 4 THEN 'PDT CONFIRMACION' END estado,
                        UPPER(pc.comentario) AS comentario,
						(SELECT UPPER(nom_estacion) 
						   FROM ebc_ubicacion e
						  WHERE e.codigo = pc.facilidades_de_red limit 1) nom_ebc						
                    FROM (planobra_cluster pc,
                        subproyecto s,
                        empresacolab e)
			   LEFT JOIN pqt_central ce ON (ce.idCentral = pc.idCentral)  		
               LEFT JOIN tipo_diseno t ON (pc.id_tipo_diseno = t.id_tipo_diseno) 
              LEFT JOIN planobra po
                     ON (pc.itemplan      = po.itemplan)
                  WHERE pc.flg_tipo       = 2 -- REGISTRO INDIVIDUAL
                    AND CASE WHEN pc.sisego IS NOT NULL THEN pc.sisego = COALESCE(?, pc.sisego)
                             ELSE true END
                    AND s.idSubProyecto   = pc.idSubProyecto       
                    AND pc.codigo_cluster = COALESCE(?, pc.codigo_cluster)
                    AND pc.idSubProyecto  = COALESCE(?, pc.idSubProyecto)
                    AND pc.estado         = COALESCE(?, pc.estado)
					AND CASE WHEN ? = '' OR ? IS NULL THEN true ELSE pc.itemplan = ? END
					AND pc.flg_paquetizado = 2
					AND pc.idEmpresaColab = e.idEmpresaColab
					AND CASE WHEN ce.idCentral IS NOT NULL THEN ce.idJefatura = COALESCE(?, ce.idJefatura)
							 ELSE true END
					AND pc.idEmpresaColab = COALESCE(?, e.idEmpresaColab)

                    AND CASE WHEN ? IS NOT NULL THEN pc.flg_rech_conf_ban_conf = ?
                             ELSE TRUE END
					AND CASE WHEN " . $ideecc . " = 0 OR " . $ideecc . " = 6 THEN true
                             ELSE pc.idEmpresaColab = " . $ideecc . " END";
        $result = $this->db->query($sql, array(
            $sisego, $codigo, $idSubProyecto, $estado, $itemplan, $itemplan, $itemplan, $idJefatura, $idEmpresaColab, $flgBandConf, $flgBandConf,
            $sisego, $codigo, $idSubProyecto, $estado, $itemplan, $itemplan, $itemplan, $idJefatura, $idEmpresaColab, $flgBandConf, $flgBandConf
        ));
        if ($flgDetalle == 1) {
            return $result->row_array();
        } else {
            return $result->result_array();
        }
    }

    function getCentralByDistrito($distrito, $idTipoCentral)
    {
        $sql = "SELECT * 
                  FROM central 
                 WHERE distrito      = COALESCE(?, distrito) 
                   AND idTipoCentral IN (1,2)
                   Limit 1";
        $result = $this->db->query($sql, array($distrito));
        return $result->row_array();
    }

    function getEmpresaColabByDesc($eecc)
    {
        $sql = "SELECT idEmpresaColab 
                   FROM empresacolab 
                 WHERE empresaColabDesc = UPPER(?)";
        $result = $this->db->query($sql, array($eecc));
        return $result->row_array()['idEmpresaColab'];
    }

    function getInfoCentralByDistritoEECC($distrito, $eecc)
    {
        $Query = "SELECT * 
                    FROM central 
                   WHERE distrito = ? 
                     AND idEmpresaColab = ?
                     AND idTipoCentral IN (1,2)
                 LIMIT 1";
        $result = $this->db->query($Query, array($distrito, $eecc));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getCambioPoComplejidad($itemplan, $idEstacion)
    {
        $sql = "SELECT 1 count
                  FROM po_cambio
                 WHERE itemplan   = ?
                   AND idEstacion = ?
                   AND codigo_po IS NULL
                   limit 1";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array()['count'];
    }

    function generarPoComplej($itemplan, $idEstacion, $idUsuario, $nroAmp, $nroTroba, $idTipoComplejidad, $codigo_po)
    {
        $sql = "SELECT fn_insertPODiseno_complejidad(?, ?, ?, 0, 0, ?, ?) AS resp";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $idUsuario, $idTipoComplejidad, $codigo_po));
        return $result->row_array()['resp'];
    }

    function getEECCByDistrito($distrito)
    {
        $query = " SELECT DISTINCT ec.idEmpresaColab, ec.empresaColabDesc, 1 AS flgTipoSubProyecto
                     FROM central c, empresacolab ec
                    WHERE c.idEmpresaColab = ec.idEmpresaColab
                      -- AND c.flg_subproByNodoCV IS NULL
                      AND c.distrito = ?";
        $result = $this->db->query($query, array($distrito));
        return $result->result();
    }

    function updateEstadoPlanObraToBanEjecucion($itemplan, $estadoPlan, $idEstacion)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $dataUpdate = array(
                "idEstadoPlan" => $estadoPlan
            );

            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);

            $this->db->where('itemPlan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('pre_diseno', array("estado" => 3));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el updateEstadoPlanObra');
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

    function getAllJefaturaToSiomReport()
    {
        $Query = "  SELECT *
	                FROM jefatura
                    WHERE flgActivo = 1
	                ORDER BY descripcion";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function insertLogCotizacionInd($dataArray)
    {
        $this->db->insert('log_planobra_cotizacion_sisego', $dataArray);
        if ($this->db->affected_rows() != 1) {
            $rpta['error'] = EXIT_ERROR;
            $rpta['msj'] = 'Error al insertar el log!';
        } else {
            $rpta['error'] = EXIT_SUCCESS;
            $rpta['msj'] = 'Se inserto correctamente!';
        }
        return $rpta;
    }

    function insertLogCotizacionIndBySisego($sisego)
    {
        $sql = "INSERT INTO log_planobra_cotizacion_sisego  (codigo_cluster,fecha,id_usuario,estado)
                SELECT codigo_cluster,fecha_aprobacion, 1645, 3
                  FROM planobra_cluster 
                 WHERE sisego = ?";
        $this->db->query($sql, array($sisego));

        if ($this->db->affected_rows() > 0) {
            $rpta['error'] = EXIT_SUCCESS;
            $rpta['msj'] = 'Se inserto correctamente!';
        } else {
            $rpta['error'] = EXIT_ERROR;
            $rpta['msj'] = 'Error al insertar el log!';
        }
    }

    function getLogCotizacionInd($codigo_cluster)
    {
        $sql = "SELECT lg.codigo_cluster,
                        lg.estado,
                        UPPER(u.nombre)AS nombre,
                        lg.fecha,
                        CASE WHEN lg.estado = 0 THEN 'SISEGO SOLICITA COTIZACIÓN'
                            WHEN lg.estado = 7 THEN 'SE MANDO A BANDEJA CONFIRMACIÓN'
                            WHEN lg.estado = 5 THEN 'VALIDADO EN BANDEJA CONFIRMACIÓN'
                            WHEN lg.estado = 6 THEN 'RECHAZADO EN BANDEJA CONFIRMACIÓN, CAMBIO A PDTE COTIZACIÓN'
                            WHEN lg.estado = 1 THEN 'SE ENVIO COTIZACIÓN'
                            WHEN lg.estado = 3 THEN 'RECHAZADO' END accion
                  FROM log_planobra_cotizacion_sisego lg,
                       usuario u
                 WHERE lg.codigo_cluster = ?
                   AND lg.id_usuario = u.id_usuario
                   ORDER BY lg.fecha ASC";
        $result = $this->db->query($sql, array($codigo_cluster));
        return $result->result_array();
    }

    function hasParalizadoAfterPreAprob($itemplan)
    {
        $Query = "SELECT po.indicador,pa.itemplan
		          FROM paralizacion pa,
				       planobra po
                 WHERE pa.itemplan = po.itemPlan
				   AND pa.idMotivo = 11
		           AND pa.idUsuario = 265
		           AND pa.comentario = 'FALTA DE PRESUPUESTO'
		           AND pa.flg_activo = 1
		           AND pa.fechaReactivacion IS NULL
                   and pa.itemplan = ?
                LIMIT  1";
        $result = $this->db->query($Query, array($itemplan));
        return $result->result();
    }

    function execGetGrafosOnePtr($ptr)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->query("SELECT loadPresupuestoMatOnePtr('" . $ptr . "');");
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error getGrafoOnePTR()');
            }
        } catch (Exception $e) {
            $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;
    }

    function sendParalizadoSisego($itemplanPO)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $arrayGlobLogTrama = array();
            $arrayGlobLogTramaDespara = array();
            $arrayIPParalizados = array();
            $arrayIPDespara = array();

            $listaIPParalizados = $this->getIPParalizados2();
            if ($listaIPParalizados != null) {
                foreach ($listaIPParalizados as $row) {
                    $arrayTemp = array(
                        "origen" => 'MODELO TRANFERENCIA WU',
                        "itemplan" => $row->itemplan,
                        "sisego" => $row->indicador,
                        "fecha_registro" => $this->fechaActual(),
                        "motivo_error" => null,
                        "descripcion" => null,
                        "estado" => null,
                    );
                    array_push($arrayIPParalizados, $row->itemplan);
                    array_push($arrayGlobLogTrama, $arrayTemp);
                }
            } else {
                $arrayIPParalizados = array();
            }
            $motivo = 'SIN PRESUPUESTO_(PEP)';
            $comentario = 'FALTA DE PRESUPUESTO';
            $nombreUsuario = $this->session->userdata('usernameSession');
            $correo = $this->session->userdata('correo');
            $flgJson = 1;
            if (count($arrayIPParalizados) > 0) {
                $dataSend = [
                    'itemplan' => json_encode($arrayIPParalizados),
                    'motivo' => $motivo,
                    'flg_activo' => 1,
                    'comentario' => $comentario,
                    'nombreUsuario' => $nombreUsuario,
                    'correo' => $correo,
                    'json' => $flgJson,
                    'fecha' => date("Y-m-d")
                ];

                log_message('error', 'data send:' . print_r($dataSend, true));
                $url = 'https://172.30.5.10:8080/obras2/recibir_par_masivo.php';
                $response = $this->sendDataToURL($url, $dataSend);

                $motivoError = '';
                $descripcion = '';
                $estado = null;

                if (!$response) {
                    $motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
                    $descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE NO SE LOGRO LA CONEXION';
                    $estado = 3;
                } else {
                    if ($response->error == EXIT_SUCCESS) {
                        $motivoError = 'TRAMA COMPLETADA';
                        $descripcion = 'OPERACION REALIZADA CON EXITO';
                        $estado = 1;
                    } else {
                        $motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
                        $descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:' . strtoupper($response->mensaje ? $response->mensaje : '');
                        $estado = 2;
                    }
                }

                $arrayTempEnvio = array();
                $arrayGlobEnvio = array();
                foreach ($arrayGlobLogTrama as $row) {
                    $arrayTempEnvio = array(
                        "origen" => $row['origen'],
                        "ptr" => 'PARALIZACION PRESUPUESTO',
                        "itemplan" => $row['itemplan'],
                        "sisego" => $row['sisego'],
                        "fecha_registro" => $row['fecha_registro'],
                        "motivo_error" => $motivoError,
                        "descripcion" => $descripcion,
                        "estado" => $estado
                    );
                    array_push($arrayGlobEnvio, $arrayTempEnvio);
                }
                $data = $this->insertBatchLogSigoplus($arrayGlobEnvio);
                if ($data['error'] == EXIT_SUCCESS) {
                    $listaIPDespara = $this->getIPDesParalizados();
                    if ($listaIPDespara != null) {
                        foreach ($listaIPDespara as $row) {
                            $arrayTempDes = array(
                                "origen" => 'MODELO TRANFERENCIA WU',
                                "itemplan" => $row->itemplan,
                                "sisego" => $row->indicador,
                                "fecha_registro" => $this->fechaActual(),
                                "motivo_error" => null,
                                "descripcion" => null,
                                "estado" => null,
                            );
                            array_push($arrayIPDespara, $row->itemplan);
                            array_push($arrayGlobLogTramaDespara, $arrayTempDes);
                        }
                    } else {
                        $arrayIPDespara = array();
                    }
                    if (count($arrayIPDespara) > 0) {
                        $dataSend2 = [
                            'itemplan' => json_encode($arrayIPDespara),
                            'motivo' => 'DESPARALIZAR',
                            'flg_activo' => 0,
                            'comentario' => 'DESPARALIZADOS',
                            'nombreUsuario ' => $nombreUsuario,
                            'correo' => $correo,
                            'json' => $flgJson,
                            'fecha' => date("Y-m-d")
                        ];
                        $response2 = $this->sendDataToURL($url, $dataSend2);
                        $motivoError = '';
                        $descripcion = '';
                        $estado = null;
                        if (!$response2) {
                            $motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
                            $descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE NO SE LOGRO LA CONEXION';
                            $estado = 3;
                        } else {
                            if ($response2->error == EXIT_SUCCESS) {
                                $motivoError = 'TRAMA COMPLETADA';
                                $descripcion = 'OPERACION REALIZADA CON EXITO';
                                $estado = 1;
                            } else {
                                $motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
                                $descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:' . strtoupper($response2->mensaje ? $response2->mensaje : '');
                                $estado = 2;
                            }
                        }
                        $arrayTempEnvio2 = array();
                        $arrayGlobEnvio2 = array();
                        foreach ($arrayGlobLogTramaDespara as $row) {
                            $arrayTempEnvio2 = array(
                                "origen" => $row['origen'],
                                "ptr" => 'DESPARALIZACION PRESUPUESTO',
                                "itemplan" => $row['itemplan'],
                                "sisego" => $row['sisego'],
                                "fecha_registro" => $row['fecha_registro'],
                                "motivo_error" => $motivoError,
                                "descripcion" => $descripcion,
                                "estado" => $estado
                            );
                            array_push($arrayGlobEnvio2, $arrayTempEnvio2);
                        }
                        $data = $this->insertBatchLogSigoplus($arrayGlobEnvio2);
                    }
                }
            } else {
                log_message('error', 'es 0:');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;
    }

    /*     * *10.07.2019 METODO CANCELA PO ASOCIADAS ITEMPLAN czavalacas** */

    public function cambiarEstadoPoMasivo($listaPoUpdate, $listaPoInsertLog)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->update_batch('planobra_po', $listaPoUpdate, 'codigo_po');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al update planobra_po.');
            } else {
                $this->db->insert_batch('log_planobra_po', $listaPoInsertLog);
                //log_message('error', $this->db->last_query());                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en log_planobra_po');
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

    /*     * ************************************************** */

    function generarPODiseno($itemplan, $idEstacion, $nro_amplificadores, $nro_trobas)
    {
        $sql = "SELECT fn_insertPODiseno(?, ?, 4, 0, 0) AS resp limit 1";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array()['resp'];
    }

    /*     * *czavala cas 18.07.2019* */

    function getEeccDisenoOperaByItemPlanPin($itemplan)
    {
        $Query = "SELECT   po.idEmpresaColabDiseno,
	                       c.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po,
	                       pqt_central c
	               WHERE   po.idCentralPqt = c.idCentral
	               AND     po.itemplan     = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getRowCotizacion($codigoCotizacion)
    {
        $sql = "SELECT * 
	              FROM planobra_cluster
	             WHERE codigo_cluster = ?";
        $result = $this->db->query($sql, array($codigoCotizacion));
        return $result->row_array();
    }

    function getAllSubProyectoPaquetizadoDesc()
    {
        $Query = "  SELECT     subproyecto.idSubProyecto,
							   subproyecto.tiempo,
							   proyecto.proyectoDesc,
							   subproyecto.subProyectoDesc,
							   tipoplanta.tipoPlantadesc,
							   subproyecto.paquetizado_fg,
							   gerente,
							   jefe_tdp,
							   supervisor,
                               gerencia.gerenciaDesc,
							   prom.nom_promotor
					   FROM    (subproyecto, proyecto, tipoplanta)
					LEFT JOIN  (SELECT t.idSubProyecto,
									   TRIM(GROUP_CONCAT(' ',CASE WHEN count_gerente  >= 1 AND uxr.idRol = 1 THEN UPPER(u.nombre) ELSE null END)) gerente,
									   TRIM(GROUP_CONCAT(' ',CASE WHEN (count_jefe_tdp >= 1 OR count_sup_jefe_tdp >= 1) AND uxr.idRol IN (2,5) THEN UPPER(u.nombre) ELSE null END)) jefe_tdp,
									   TRIM(GROUP_CONCAT(' ',CASE WHEN count_jefe_tdp >= 1 AND uxr.idRol = 3 THEN UPPER(u.nombre) ELSE null END)) jefe_ecc,
									   TRIM(GROUP_CONCAT(' ',CASE WHEN (count_jefe_tdp >= 1 OR count_sup_jefe_tdp) >= 1 AND uxr.idRol IN (4,5) THEN UPPER(u.nombre) ELSE null END)) supervisor,
									   TRIM(GROUP_CONCAT(' ',CASE WHEN count_jefe_tdp >= 1 AND uxr.idRol = 5 THEN UPPER(u.nombre) ELSE null END)) sup_jefe_tdp
								  FROM usuario_x_subproyecto_valida_acta uxs,
									   usuario u,
									   usuario_x_rol uxr,
									   (SELECT uxs.idSubProyecto,
											   SUM(CASE WHEN uxr.idRol = 1 THEN 1 ELSE 0 END) count_gerente,
											   SUM(CASE WHEN uxr.idRol = 2 THEN 1 ELSE 0 END) count_jefe_tdp,
											   SUM(CASE WHEN uxr.idRol = 3 THEN 1 ELSE 0 END) count_jefe_ecc,
											   SUM(CASE WHEN uxr.idRol = 4 THEN 1 ELSE 0 END) count_sup, 
											   SUM(CASE WHEN uxr.idRol = 5 THEN 1 ELSE 0 END) count_sup_jefe_tdp
										  FROM usuario_x_subproyecto_valida_acta uxs, 
											   usuario u,
											   usuario_x_rol uxr
										 WHERE uxs.idUsuario = u.id_usuario
										   AND uxr.idUsuario = u.id_usuario 
										GROUP BY uxs.idSubProyecto
									)t
									WHERE uxs.idSubProyecto = t.idSubProyecto
									  AND u.id_usuario = uxs.idUsuario
									  AND uxr.idUsuario = u.id_usuario
								GROUP BY t.idSubProyecto)tt ON tt.idSubProyecto = subproyecto.idSubProyecto
                       LEFT JOIN gerencia ON proyecto.idGerencia = gerencia.idGerencia
					   LEFT JOIN promotor prom ON subproyecto.id_promotor = prom.id_promotor
					   WHERE   subproyecto.idProyecto = proyecto.idProyecto
					   AND     subproyecto.idTipoPlanta = tipoplanta.idTipoPlanta
					GROUP BY idSubproyecto
					ORDER BY   proyectoDesc, subProyectoDesc;";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getFactorDeMedicionXIdSubProyecto($idSubProyecto)
    {
        $query = "SELECT fm.idPqtTipoFactorMedicion, fm.descPqtTipoFactorMedicion
                    FROM subproyecto s 
                    INNER JOIN pqt_tipo_factor_medicion fm 
                    ON s.idPqtTipoFactorMedicion = fm.idPqtTipoFactorMedicion
                    WHERE s.idSubProyecto = ?;";
        $result = $this->db->query($query, array($idSubProyecto));
        return $result->row_array();
    }

    /** COMENTADO POR CZAVALA CAS 26.11.2019 EDIT SUBPROYECTO * */
    function getAllIdEstacionesPaquetizado()
    {
        $Query = "  SELECT e.idEstacion, e.estacionDesc
                	FROM   estacion_transporte e
	                WHERE 1 = 1 
					#AND e.idEstacion IN (" . ESTACION_PQT_DISENO . "," . ESTACION_PQT_COAXIAL . "," . ESTACION_PQT_ENERGIA . "," . ESTACION_PQT_FO . "," . ESTACION_PQT_FUENTE . "," . ESTACION_PQT_OC_COAXCIAL . "," . ESTACION_PQT_OC_FO . ")
                	ORDER BY idEstacion";
        $result = $this->db->query($Query, array());
        return $result;
    }

    public function insertarLogPqtCentral($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        // 	    log_message('error', 'insertarLogPqtCentral $arrayInsert:'.print_r($arrayInsert,true));
        try {
            $this->db->trans_begin();
            $this->db->insert('log_pqt_central', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_central');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getZonalXPqtCentral($idCentral)
    {
        $Query = "  SELECT zonal.idzonal,zonal.zonalDesc
	    			from pqt_central left join zonal on pqt_central.idzonal=zonal.idzonal
					where pqt_central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        return $result;
    }

    function getEECCXPqtCentral($idCentral, $flgRow = null)
    {
        $Query = "  SELECT empresacolab.idEmpresaColab,empresacolab.empresaColabDesc  from pqt_central
					left join empresacolab on pqt_central.idEmpresaColab=empresacolab.idEmpresaColab
					where pqt_central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        if ($flgRow == null) {
            return $result;
        } else {
            return $result->row_array();
        }
    }

    function getJefaturaXPqtCentral($idCentral)
    {
        $Query = "  SELECT pqt_central.jefatura
	    			from pqt_central
					where pqt_central.idcentral=?";
        $result = $this->db->query($Query, array($idCentral));
        return $result;
    }

    function getIdPqtCentralByPqtCentralDesc($codigo)
    {
        $Query = "SELECT idCentral, idZonal, idEmpresaColab from pqt_central where codigo = ?;";
        $result = $this->db->query($Query, array($codigo));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getCodigoPqtCentral($jefatura)
    {
        $sql = "SELECT distinct codigo
    			  FROM pqt_central
    			 WHERE jefatura = '" . $jefatura . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    //USUARIO QUE SE PERMITIRA EL ACCESO

    function getIdUsuarioAccess($usuario)
    {
        $sql = "SELECT 1 flg_access 
	              FROM usuario 
	             WHERE usuario IN (
                                    'owen'
                                    )
                   AND usuario = ? 
                  LIMIT 1";
        $result = $this->db->query($sql, array($usuario));
        return $result->row_array()['flg_access'];
    }

    //GUSTAVO 2019 08 15 CONSULTA DE PLAN OBRA EN PAQUETIZADO
    function getAllSubProyectoByProyectoPqt($idProyecto, $flgRegitroCableadoEdif = null)
    {
        $Query = "  SELECT *
	                FROM subproyecto
	                WHERE idProyecto = ?
					  AND CASE WHEN ? = 1 AND idProyecto = 21 THEN idSubProyecto NOT IN (96,97,98,99)
					           ELSE TRUE END
	                  AND SUBSTRING_INDEX( subproyectoDesc , ' ', 1 ) NOT IN(2016,2017)
	                   AND paquetizado_fg = 2
	                ORDER BY subProyectoDesc";
        $result = $this->db->query($Query, array($idProyecto, $flgRegitroCableadoEdif));
        return $result;
    }

    function getDiasMatriz($totalMetros, $seia, $mtc, $inc, $flgTipoZona, $jefatura)
    {
        $sql = "SELECT seia, mtc, dias 
                  FROM cotizacion_matriz_dias 
                  WHERE CASE WHEN met_in IS NULL THEN ? <= met_fin
                            WHEN met_fin IS NULL THEN met_in < ?
                            WHEN met_in IS NOT NULL AND met_fin IS NOT NULL AND ? = met_in  THEN ? BETWEEN met_in+1 AND met_fin 
                            WHEN met_in IS NOT NULL AND met_fin IS NOT NULL AND ? <> met_in THEN ? BETWEEN met_in AND met_fin END
                   AND seia = COALESCE(?, seia)
                   AND mtc  = COALESCE(?, mtc)
                   AND inc  = COALESCE(?, inc)
                   AND flg_tipo_zona = ?
                   AND CASE WHEN ? = 'LIMA' THEN flg_lima_provincia = 1
                            ELSE flg_lima_provincia = 2 END
                GROUP BY seia, mtc";
        $result = $this->db->query($sql, array(
            $totalMetros, $totalMetros, $totalMetros, $totalMetros, $totalMetros, $totalMetros, $seia,
            $mtc, $inc, $flgTipoZona, $jefatura
        ));
		_log($this->db->last_query());
        return $result->row_array();
    }

    function getDataCentralById($idCentral)
    {
        $sql = "SELECT idCentral, 
                       idTipoCentral, 
                       jefatura, 
                       idJefatura,
                       flg_tipo_zona,
                       idEmpresaColab
                  FROM central 
                 WHERE idCentral = ?";
        $result = $this->db->query($sql, array($idCentral));
        return $result->row_array();
    }

    function getDataSisego($sisego, $flgPrincipal)
    {
        $sql = "SELECT COUNT(1) count 
                  FROM planobra_cluster 
                 WHERE sisego        = ?
                   AND flg_principal = ?
				   AND estado <> 3";
        $result = $this->db->query($sql, array($sisego, $flgPrincipal));
        return $result->row_array()['count'];
    }

    function getCountConfirmaSisego($codigo)
    {
        $sql = "SELECT COUNT(1) count
                  FROM cotizacion_validar
                 WHERE codigo_cluster = ?
                   AND flg_validacion <> 2 ";
        $result = $this->db->query($sql, array($codigo));
        return $result->row_array()['count'];
    }

    function getDataCotizacionByItemplan($itemplan)
    {
        $sql = "SELECT DISTINCT 
                        po.itemplan,
                        po.indicador,
                        pc.fecha_registro,
                        po.fecha_creacion,
                        pc.estado,
                        pc.flg_principal,
                        po.idEstadoPlan,
                        pc.codigo_cluster,
                        pc.nodo_principal,
                        pc.nodo_respaldo,
                        pc.facilidades_de_red, 
                        pc.cant_cto, 
                        pc.metro_tendido_aereo, 
                        pc.metro_tendido_subterraneo,
                        pc.metors_canalizacion,
                        pc.cant_camaras_nuevas, 
                        pc.cant_postes_nuevos,
                        pc.cant_postes_apoyo,
                        pc.cant_apertura_camara,
                        pc.requiere_seia,
                        pc.requiere_aprob_mml_mtc,
                        pc.requiere_aprob_inc,
                        pc.duracion,
                        pc.id_tipo_diseno,
                        pc.costo_materiales,
                        pc.costo_mano_obra,
                        pc.costo_diseno,
                        pc.costo_expe_seia_cira_pam,
                        pc.costo_adicional_rural,
                        pc.costo_total
                  FROM planobra po, planobra_cluster pc 
                 WHERE CONCAT(pc.sisego,'-',pc.flg_principal) = po.indicador
                   AND po.idEstadoPlan <> 6
                   AND pc.estado <> 3
                   AND po.itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    function getDataArbolNav($idUsuario, $idPadre)
    {
        $sql = " SELECT u.nombre,
                        u.usuario,
                        UPPER(pe.descripcion)descripcion,
                        pe.route,
                        pe.icono,
                        p.id_perfil,
                        p.desc_perfil,
                        pe.id_padre,
                        pe.fg_modulo,
                        pe.orden
                   FROM permisos_x_perfil pf,
                        perfil p,
                        usuario u,
                        permisos pe
                  WHERE pf.id_perfil = p.id_perfil
                    AND FIND_IN_SET(p.id_perfil, u.id_perfil)
                    AND u.id_usuario = ?
                    AND pe.id_permiso = pf.id_permiso
                    AND pe.id_padre  = ?
                    AND pe.flg_panel = 1
                ORDER BY pe.orden";
        $result = $this->db->query($sql, array($idUsuario, $idPadre));
        return $result->result_array();
    }

    function getDataPanelPermiso($idUsuario, $idPadre, $flgMostarPadreHijo, $flgPanel = NULL)
    {
        $sql = "SELECT  pe.id_permiso,
                        id_padre, 
                        flg_panel, 
                        logo_panel, 
                        pe.descripcion,
                        ( SELECT GROUP_CONCAT(descripcion,'|',logo_panel)
                            FROM permisos 
                            WHERE id_permiso = pe.id_padre) dataPadre,
                        pe.route    
                FROM  permisos_x_perfil pf,
                        perfil p,
                        usuario u,
                        permisos pe
                WHERE pf.id_perfil = p.id_perfil
                    AND FIND_IN_SET(p.id_perfil, u.id_perfil)
                    AND u.id_usuario = ?
                    AND pe.id_permiso = pf.id_permiso
                    AND pe.id_padre  = COALESCE(?, pe.id_padre)
                    AND pe.flg_panel = ?
                    AND pe.id_padre <> 24
                    GROUP BY CASE WHEN ? = 1 THEN  id_padre 
                                  ELSE pe.id_permiso END
                ORDER BY pe.orden";
        $result = $this->db->query($sql, array($idUsuario, $idPadre, $flgPanel, $flgMostarPadreHijo)); //1 : MOSTRAR PADRE y 2 : HIJOS
        // _log($this->db->last_query());
        return $result->result_array();
    }

    function getContratosAll()
    {
        $sql = "SELECT id_contratos,
                       nombre,
                       alias,
                       observacion,
                       descripcion,
                       fecha_inicio,
                       fecha_termino
                  FROM contratos
                  ORDER BY nombre";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getAllEmpresaColab($flg_solicitud_usuar_siom, $id_contrato = null)
    {
        $sql = "  SELECT *
                    FROM empresacolab e, contrato_x_empresacolab ce
                   -- WHERE e.idEmpresacolab NOT IN (5,6,9)
                   WHERE flg_solicitud_usua_siom = COALESCE(?, flg_solicitud_usua_siom)  
				     AND ce.id_contrato = COALESCE(?, ce.id_contrato)
					 AND e.idEmpresaColab = ce.idEmpresaColab
                ORDER BY empresaColabDesc, ce.id_contrato";
        $result = $this->db->query($sql, array($flg_solicitud_usuar_siom, $id_contrato));
        return $result->result_array();
    }

    function getZona($flg_contrato)
    {
        $sql = "SELECT id_zona,
                       nombre,
                       alias,
                       descripcion
                  FROM zona
			WHERE flg_contrato = ?
			ORDER BY nombre";
        $result = $this->db->query($sql, array($flg_contrato));
        return $result->result_array();
    }

    function getPerfilAll($flg_solicitud_usua_siom)
    {
        $sql = "SELECT id_perfil,
                       desc_perfil
                  FROM perfil
                 WHERE flg_solicitud_usua_siom = COALESCE(?, flg_solicitud_usua_siom)";
        $result = $this->db->query($sql, array($flg_solicitud_usua_siom));
        return $result->result_array();
    }

    function countSolicitudSiomByDni($dni, $estado)
    {
        $sql = "SELECT COUNT(1) count
                  FROM solicitud_usuario 
                 WHERE dni    = ?
                   AND estado = ?";
        $result = $this->db->query($sql, array($dni, $estado));
        return $result->row_array()['count'];
    }

    function countUsuarioActivo($dni)
    {
        $sql = "SELECT COUNT(1) count
                  FROM usuario_siom 
                 WHERE dni    = ?";
        $result = $this->db->query($sql, array($dni));
        return $result->row_array()['count'];
    }

    public function getIPParalizados2()
    {
        $sql = "SELECT po.indicador,pa.itemplan
		          FROM paralizacion pa,
				       planobra po
                 WHERE pa.itemplan = po.itemPlan
				   AND pa.idMotivo = 11
		           AND pa.idUsuario = 265
		           AND pa.comentario = 'FALTA DE PRESUPUESTO'
		           AND pa.flg_activo = 1
		           AND pa.fechaReactivacion IS NULL
                   and pa.send = 1;";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getIPDesParalizados()
    {
        $sql = "SELECT po.indicador,pa.itemplan
		          FROM paralizacion pa,
				       planobra po
                 WHERE pa.itemplan = po.itemPlan
				   AND pa.idMotivo = 11
		           AND pa.idUsuario = 265
		           AND pa.comentario = 'FALTA DE PRESUPUESTO'
		           AND pa.flg_activo = 0
		           AND pa.fechaReactivacion IS NOT NULL
                   and pa.send = 1;";

        $result = $this->db->query($sql);
        return $result->result();
    }

    /*     * *czavala cas 14.10.2019* */

    function getAllEstacion()
    {
        $Query = " SELECT * FROM estacion";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllSubProyectoByIdProyecto($idProyecto)
    {
        $Query = "  SELECT * 
                    FROM       subproyecto 
                    WHERE      idProyecto = ?
	                ORDER BY   subProyectoDesc";
        $result = $this->db->query($Query, array($idProyecto));
        return $result;
    }

    function getInfoItemToSendSiropeEjecucionDiseno($idEstacion, $itemplan)
    {
        $Query = "SELECT
                    po.itemplan,
                    po.fechaInicio,
                    c.jefatura,
                    DATE_FORMAT(pd.fecha_prevista_atencion, '%Y-%m-%d') AS fecha_prevista
                FROM
                    planobra po,
                    central c,
                    pre_diseno pd
                WHERE
                    po.idCentral = c.idCentral
                        AND pd.itemplan = po.itemplan
                        AND pd.idEstacion = ?
                        AND po.itemplan = ?";
        $result = $this->db->query($Query, array($idEstacion, $itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getMotivosObserValidados()
    {
        $Query = " SELECT * FROM motivo_observacion_validacion";
        $result = $this->db->query($Query, array());
        return $result->result();
    }

    function getMicroCanalizadoOCPlanobraDetalleCV($itemplan)
    {
        $Query = 'SELECT microcanalizado_oc
                    FROM planobra_detalle_cv
                   WHERE itemplan = ?';
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['microcanalizado_oc'];
        } else {
            return null;
        }
    }

    function getCTOCotizacion($clasificacion, $tipo_cliente)
    {
        $sql = "SELECT m.cto,
                       UPPER(t.descripcion) as tipo_diseno,
					   t.id_tipo_diseno,
                       m.tiempo,
                       m.costo_total
                  FROM matriz_cto m,
                       tipo_diseno t
                 WHERE m.clasificacion = ?
                   AND m.id_tipo_diseno = t.id_tipo_diseno
                   AND m.tipo_cliente  = ?";
        $result = $this->db->query($sql, array($clasificacion, $tipo_cliente));
        return $result->row_array();
    }

    function getCTOCotizacion_v2($clasificacion, $tipo_cliente)
    {
        $sql = "SELECT m.cto,
                       UPPER(t.descripcion) as tipo_diseno,
					   t.id_tipo_diseno,
                       m.tiempo,
                       m.costo_total
                  FROM matriz_cto_v2 m,
                       tipo_diseno t
                 WHERE UPPER(m.clasificacion) = ?
                   AND m.id_tipo_diseno = t.id_tipo_diseno
                   AND m.tipo_cliente  = ?";
        $result = $this->db->query($sql, array($clasificacion, $tipo_cliente));
        return $result->row_array();
    }

    function getIdTipoDisenoByDescDiseno($tipo_diseno_desc)
    {
        $sql = "SELECT id_tipo_diseno 
		          FROM tipo_diseno
				 WHERE descripcion = ?";
        $result = $this->db->query($sql, array($tipo_diseno_desc));
        return $result->row_array()['id_tipo_diseno'];
    }

    function getCostosMatrizCotizacion($rango, $req_eia, $req_inc)
    {
        $sql = "SELECT t.mo_total,
                       t.mat_total,
					   t.eia_total,
					   t.inc_total,
					   t.diseno_total,
							t.diseno_total+
							t.mo_total+
						    t.mat_total+
						    t.eia_total+
						    t.inc_total AS total
		          FROM ( 
						 SELECT mo_total,
								mat_total,
								diseno_total,
								total,
								CASE WHEN ? = 'NO' THEN 0 ELSE eia_total END eia_total,
								CASE WHEN ? = 'NO' THEN 0 ELSE inc_total END inc_total
						   FROM cotizacion_matriz_costos
						  WHERE met_in <= ?
							AND ? <= met_fin
							AND flg_version = 1
						)t";
        $result = $this->db->query($sql, array($req_eia, $req_inc, $rango, $rango));
        return $result->row_array();
    }

    function getCostosMatrizCotizacionV2($rango, $req_eia, $req_inc, $flg_version, $id_tipo_diseno)
    {
        $sql = "SELECT t.mo_total,
                       ROUND(t.mat_total,0) mat_total,
					   t.eia_total,
					   t.inc_total,
					   t.diseno_total,
							t.diseno_total+
							t.mo_total+
						    t.mat_total+
						    t.eia_total+
						    t.inc_total AS total,
                        t.metro_oc,
                        t.crxa,
                        t.crxc,
						t.costo_oc,
                        t.postes,
						t.costo_mo_edif, 
						t.costo_mat_edif, 
						t.costo_oc_edif,
						ROUND(t.costo_mo_edif + t.costo_mat_edif + t.costo_oc_edif, 0) costo_edif,
						t.costo_total_um
		          FROM ( 
						 SELECT ROUND((mo_total+costo_oc),0) mo_total,
								ROUND(costo_oc, 0) costo_oc,
                                CASE WHEN ROUND(" . $rango . ",0) <= 200 AND " . $id_tipo_diseno . " != 7 THEN mat_total 
                                     ELSE ROUND((ROUND(" . $rango . ",0)*costo_metro_mat+mat_total),2) END AS mat_total,
								diseno_total,
                                total,
								CASE WHEN ? = 'NO' THEN 0 ELSE eia_total END eia_total,
                                CASE WHEN ? = 'NO' THEN 0 
								     WHEN ? = 'SI' AND metro_oc = 0 THEN 0 
								     ELSE inc_total END inc_total,
                                metro_oc,
                                crxa,
                                crxc,
                                postes,
								ROUND(costo_mo_edif,0) costo_mo_edif, 
								ROUND(costo_mat_edif,0)costo_mat_edif, 
								ROUND(costo_oc_edif,0) costo_oc_edif,
								costo_total_um
						   FROM cotizacion_matriz_costos
						  WHERE met_in <= ?
							AND ? <= met_fin
                            AND flg_version = ?
						)t";
        $result = $this->db->query($sql, array($req_eia, $req_inc, $req_inc, $rango, $rango, $flg_version));
        return $result->row_array();
    }

    function getDataCoordenadasNodo()
    {
        $sql = "SELECT DISTINCT
					   idCentral,
					   codigo,
					   latitud,
					   longitud,
					   idZonal,
					   idEmpresaColab,
					   CONCAT(codigo,' - ',tipoCentralDesc) nom_central,
					   departamento,
					   distrito
				  FROM pqt_central 
				 WHERE latitud <> ''
				   AND latitud IS NOT NULL
				   AND idTipoCentral IN (1)";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    /*     * SOLO FO VA A SIROPE* */

    function getInfoItemplanToSiropeTrama($itemplan)
    {
        $Query = 'SELECT po.itemplan, po.fechaInicio, DATE_FORMAT(pd.fecha_prevista_atencion, "%Y-%m-%d") as fecha_prevista_atencion
					FROM planobra po, pre_diseno pd 
					where po.itemplan = pd.itemplan and pd.idEstacion = 5
					and po.itemplan = ?
					LIMIT 1';
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getDataCoordenadasCto()
    {
        $sql = "SELECT id_t,
					   latitud,
					   longitud,
					   codigo
				  FROM cto_ubicacion";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getFuenteRetiroCableInfoByItemplan($itemplan)
    {
        $Query = 'SELECT po.itemplan,
                	SUM(CASE WHEN ea.idEstacion = ' . ID_ESTACION_FUENTE . ' THEN 1 ELSE 0 END) as fuente,
                	SUM(CASE WHEN ea.idEstacion = ' . ID_ESTACION_RETIRO_CABLE . ' THEN 1 ELSE 0 END) as retiro_cable
                	FROM planobra po, subproyectoestacion se, estacionarea ea
                	where po.idSubProyecto = se.idSubProyecto
                	and se.idEstacionArea = ea.idEstacionArea
                	and ea.idEstacion in (' . ID_ESTACION_FUENTE . ',' . ID_ESTACION_RETIRO_CABLE . ')
                	and po.itemplan = ?
                	group by itemplan;';
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getFlgAlcancRobotCotizacion($clasificacion)
    {
        $sql = "SELECT id,
					   clasificacion,
					   flg_cotizacion_automatica,
					   flg_envio_sisego,
					   flg_crea_itemplan
				  FROM cotizacion_alcance_robot
				 WHERE UPPER(clasificacion) = UPPER(?)";
        $result = $this->db->query($sql, array($clasificacion));
        return $result->row_array();
    }

    function getFlgPaquetizadoPo($itemplan)
    {
        $sql = "SELECT paquetizado_fg 
				  FROM planobra 
				 WHERE itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['paquetizado_fg'];
    }

    function getSolicitudCotizacionRobot($flg_robot, $estado, $codigo_cot)
    {
        $sql = "SELECT pc.id_planobra_cluster,
                        pc.clasificacion, 
                        pc.tipo_cliente, 
                        pc.latitud, 
                        pc.longitud,
                        pc.codigo_cluster,
                        pc.facilidades_de_red,
                        pc.requiere_seia,
                        pc.requiere_aprob_mml_mtc,
                        pc.requiere_aprob_inc,
                        pc.fecha_registro,
                        pc.sisego,
                        pc.nodo_principal,
                        pc.flg_robot,
                        pc.clasificacion,
                        pc.tipo_cliente,
                        pc.idCentral,
                        c.codigo,
						pc.tipo_enlace,
						pc.nombre_estudio,
                        pc.tendido_externo,
						pc.segmento,
						pc.id_tipo_diseno,
						pc.flg_principal,
						pc.idSubProyecto
                  FROM planobra_cluster pc,
                       pqt_central c
                 WHERE flg_robot    = ?
                   AND estado       = ?
				   AND codigo_cluster = COALESCE(?, codigo_cluster)
                   AND pc.idCentral = c.idCentral
				   AND pc.idSubProyecto IS NOT NULL
				   AND pc.sisego NOT IN ('2020-03-158214','2020-03-158132','2020-03-158134','2020-03-158133','2020-03-158136',
							'2020-03-158138',
							'2020-03-158139',
							'2020-03-158226',
							'2020-03-158214',
							'2020-03-158141',
							'2020-03-158143',
							'2020-03-158144',
							'2020-03-158145',
							'2020-03-158147',
							'2020-03-158148',
							'2020-03-158151',
							'2020-03-158153',
							'2020-03-158156',
							'2020-03-158159',
							'2020-03-158160',
							'2020-03-158162',
							'2020-03-158163',
							'2020-03-158164',
							'2020-03-158165',
							'2020-03-158169',
							'2020-03-158170',
							'2020-03-158171',
							'2020-03-158173',
							'2020-03-158175',
							'2020-03-158177',
							'2020-03-158179',
							'2020-03-158181',
							'2020-03-158182',
							'2020-03-158183',
							'2020-03-158184',
							'2020-03-158221',
							'2020-03-158222',
							'2020-03-158224')
					GROUP BY pc.codigo_cluster";
        $result = $this->db->query($sql, array($flg_robot, $estado, $codigo_cot));
        return $result->result_array();
    }

    function updateRobotCotizacion($arrayCotizacionUpdate, $arrayLog, $dataArrayTrama)
    {
        // $this->db->trans_begin();
        $data['error'] = EXIT_ERROR;
        $dataSend = $dataArrayTrama;
        $url = 'https://172.30.5.10:8080/sisego/Requerimientos/cotizarEstudio';

        $response = $this->m_utils->sendDataToURL($url, $dataSend);

        if ($response->error == EXIT_SUCCESS) {
            $data = $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL', $dataArrayTrama['codigo'], NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3, $response);

            $this->db->where('id_planobra_cluster', $arrayCotizacionUpdate['id_planobra_cluster']);
            $this->db->update('planobra_cluster', $arrayCotizacionUpdate);

            if ($this->db->trans_status() === FALSE) {
                $data['msj'] = 'error interno en la Web PO';
                _log("ENTRO AL ROLLBACK");
                // $this->db->trans_rollback();
            } else {
                $this->db->insert('log_planobra_cotizacion_sisego', $arrayLog);

                if ($this->db->trans_status() === FALSE) {
                    $data['msj'] = 'error interno en la Web PO';
                    _log("ENTRO AL ROLLBACK");
                    // $this->db->trans_rollback();
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    _log("ENTRO AL COMMIT");
                    // $this->db->trans_commit();
                }
            }
        } else {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = "msj de SISEGO: " . $response->mensaje;
            // $this->db->trans_rollback();
            $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL', $dataArrayTrama['codigo'], NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 3, $response);
        }

        return $data;
    }

    function countCotiPrinResp($sisego, $estado)
    {
        $sql = "SELECT COALESCE(SUM(CASE WHEN flg_principal = 1 THEN 1 ELSE 0 END), 0) count_resp,
					   COALESCE(SUM(CASE WHEN flg_principal = 0 THEN 1 ELSE 0 END), 0) AS count_prin
				  FROM planobra_cluster 
				 WHERE flg_principal IN (0,1)
				   AND estado = ?
				   AND sisego = ?";
        $result = $this->db->query($sql, array($estado, $sisego));
        return $result->row_array();
    }

    function updateCotiFlgRobot($codigo_cluster, $arrayData)
    {
        $this->db->where('codigo_cluster', $codigo_cluster);
        $this->db->update('planobra_cluster', $arrayData);

        if ($this->db->trans_status() === FALSE) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se inserto!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se inserto correctamente!';
        }
    }

    function getFlgPaquetizadoCoti($codigo)
    {
        $sql = "SELECT flg_paquetizado 
				  FROM planobra_cluster 
				 WHERE codigo_cluster = ?";
        $result = $this->db->query($sql, array($codigo));
        return $result->row_array()['flg_paquetizado'];
    }

    function getDataClusterInUpdateMdf()
    {
        $sql = "SELECT pc.* 
				  FROM planobra_cluster pc, 
					   pqt_central c 
				 WHERE pc.idCentral = c.idCentral 
				   AND pc.flg_paquetizado = 2
				   AND estado IN (1,2,3,0)";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function updateMdfCotizacion($codigo_coti, $arrayData)
    {
        $this->db->where('codigo_cluster', $codigo_coti);
        $this->db->update('planobra_cluster', $arrayData);
    }

    function getIdCentralByCentralDescPqt($codigo)
    {
        $Query = "SELECT idCentral, 
                          idZonal, 
                          idEmpresaColab,
                          tipoCentralDesc,
                          latitud,
                          longitud,
                          codigo, 
						  jefatura,
						  idEmpresaColabCV,
						  jefatura
                     FROM pqt_central 
                    WHERE UPPER(codigo) = UPPER(?)
                    LIMIT 1";
        $result = $this->db->query($Query, array($codigo));
        _log($this->db->last_query());
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getCtoByCoord($lat, $long)
    {
        $sql = " SELECT GROUP_CONCAT(codigo) group_codigo
				   FROM (    
						 SELECT CASE WHEN distancia <= 600 THEN codigo 
									 ELSE NULL END codigo
						   FROM 
							 (
							  SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud
								FROM (
										SELECT pow(cos(latTo) * sin(lonDelta), 2) +
											   pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
											   sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
											   codigo,
											   latitud, longitud
										  FROM (
												SELECT radians(latitud)  AS latFrom,
													   radians(" . $long . ") - radians(longitud) AS lonDelta,
													   radians(" . $long . ") lonTo,
													  
													   radians(" . $lat . ") as  latTo,
													   codigo,
													   latitud, longitud
												  FROM cto_ubicacion
											   )t
									 )tt
								 ORDER BY distancia ASC
								 limit 5
								)ttt
						)tttt";
        $result = $this->db->query($sql);
        return $result->row_array()['group_codigo'];
    }

    function getCtoByCoordV2($lat, $long)
    {
        $sql = "  SELECT codigo, distancia, latitud, longitud, disponible_hilos, id_cto as id_terminal, flg_tipo_diseno, tecnologia
                    FROM ( 
                        SELECT CASE WHEN ROUND(distancia,0) <= 200 AND disponible_hilos > 0 THEN codigo 
                                    WHEN ROUND(distancia,0) > 200 AND ROUND(distancia,0) <= 600 AND disponible_hilos > 3 THEN codigo 
                                    ELSE NULL END codigo,
                                    ROUND(distancia,0) as distancia,
                                    latitud, longitud, disponible_hilos, id_cto,
									CASE WHEN tecnologia = 'GPON' OR (tecnologia = 'P2P' AND disponible_hilos = 0) THEN 1
										 ELSE 2 END flg_tipo_diseno,
									tecnologia,
									flg_cruce
                            FROM 
                            (
                            SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_cto, tecnologia,ubicacion_cto,
									(SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
														  GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) lg
									   FROM vias_metropolitanas_ubicacion
									   ORDER BY lg DESC
									   limit 1) flg_cruce
                                FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud, longitud,
                                                disponible_hilos,
												id_cto,
												tecnologia,
												ubicacion_cto
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                        radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                        radians(" . $long . ") lonTo,
                                                        
                                                        radians(" . $lat . ") as  latTo,
                                                        codigo,
                                                        latitud, longitud,
                                                        total_hilos-ocupacion_hilos as disponible_hilos,
														id_cto,
														tecnologia,
														ubicacion_cto
                                                   FROM cto_ubicacion
                                                )t
                                    )tt
								GROUP BY id_cto
                                HAVING (ROUND(distancia,0) <= 200 AND disponible_hilos > 0 AND ubicacion_cto IN ('1 - Poste', '6 - Cámara', '10 - Fachada')
								         AND tecnologia = 'P2P') OR
								       (ROUND(distancia,0) > 200 AND ROUND(distancia,0) <= 600 AND ROUND(disponible_hilos) > 3)
                                ORDER BY distancia ASC
                                )ttt
							HAVING flg_cruce = 0
							ORDER BY distancia ASC
							limit 1
                        )tttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    // function getCtoByCoordV2($lat, $long) {
    // $sql = "  SELECT codigo, distancia, latitud, longitud, disponible_hilos, id_cto as id_terminal, flg_tipo_diseno, tecnologia
    // FROM ( 
    // SELECT CASE WHEN ROUND(distancia,0) <= 200 AND disponible_hilos > 0 THEN codigo 
    // WHEN ROUND(distancia,0) > 200 AND ROUND(distancia,0) <= 600 AND disponible_hilos > 3 THEN codigo 
    // ELSE NULL END codigo,
    // ROUND(distancia,0) as distancia,
    // latitud, longitud, disponible_hilos, id_cto,
    // CASE WHEN tecnologia = 'GPON' OR (tecnologia = 'P2P' AND disponible_hilos = 0) THEN 1
    // ELSE 2 END flg_tipo_diseno,
    // tecnologia,
    // flg_cruce
    // FROM 
    // (
    // SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_cto, tecnologia,ubicacion_cto,
    // (SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
    // GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', ".$long." ".$lat."))'))) lg
    // FROM vias_metropolitanas_ubicacion
    // ORDER BY lg DESC
    // limit 1) flg_cruce
    // FROM (
    // SELECT pow(cos(latTo) * sin(lonDelta), 2) +
    // pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
    // sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
    // codigo,
    // latitud, longitud,
    // disponible_hilos,
    // id_cto,
    // tecnologia,
    // ubicacion_cto
    // FROM (
    // SELECT radians(latitud)  AS latFrom,
    // radians(" . $long . ") - radians(longitud) AS lonDelta,
    // radians(" . $long . ") lonTo,

    // radians(" . $lat . ") as  latTo,
    // codigo,
    // latitud, longitud,
    // total_hilos-ocupacion_hilos as disponible_hilos,
    // id_cto,
    // tecnologia,
    // ubicacion_cto
    // FROM cto_ubicacion
    // )t
    // )tt
    // GROUP BY id_cto
    // HAVING (ROUND(distancia,0) <= 200 AND disponible_hilos > 0 AND ubicacion_cto IN ('1 - Poste', '6 - Cámara', '10 - Fachada')
    // AND tecnologia = 'P2P') OR
    // (ROUND(distancia,0) > 200 AND ROUND(distancia,0) <= 600 AND ROUND(disponible_hilos) > 3)
    // ORDER BY distancia ASC
    // )ttt
    // HAVING flg_cruce = 0
    // ORDER BY distancia ASC
    // limit 1
    // )tttt";
    // $result = $this->db->query($sql);
    // return $result->row_array();
    // }

    function getCtoByCoordV2Simu($lat, $long)
    {
        $sql = "  SELECT codigo, distancia, latitud, longitud, disponible_hilos, id_cto as id_terminal, flg_tipo_diseno, tecnologia
                    FROM ( 
                        SELECT CASE WHEN ROUND(distancia,0) <= 200 AND disponible_hilos > 0 THEN codigo 
                                    WHEN ROUND(distancia,0) > 200 AND ROUND(distancia,0) <= 600 AND disponible_hilos > 3 THEN codigo 
                                    ELSE NULL END codigo,
                                    ROUND(distancia,0) as distancia,
                                    latitud, longitud, disponible_hilos, id_cto,
									CASE WHEN tecnologia = 'GPON' OR (tecnologia = 'P2P' AND disponible_hilos = 0) THEN 1
										 ELSE 2 END flg_tipo_diseno,
									tecnologia,
									flg_cruce
                            FROM 
                            (
                            SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_cto, tecnologia,ubicacion_cto,
									(SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
														  GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) lg
									   FROM vias_metropolitanas_ubicacion
									   ORDER BY lg DESC
									   limit 1) flg_cruce
                                FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud, longitud,
                                                disponible_hilos,
												id_cto,
												tecnologia,
												ubicacion_cto
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                        radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                        radians(" . $long . ") lonTo,
                                                        
                                                        radians(" . $lat . ") as  latTo,
                                                        codigo,
                                                        latitud, longitud,
                                                        total_hilos-ocupacion_hilos as disponible_hilos,
														id_cto,
														tecnologia,
														ubicacion_cto
                                                   FROM cto_ubicacion
                                                )t
                                    )tt
								GROUP BY id_cto
                                HAVING (ROUND(distancia,0) <= 200 AND disponible_hilos > 0 AND ubicacion_cto IN ('1 - Poste', '6 - Cámara', '10 - Fachada')
								         AND tecnologia = 'P2P') OR
								       (ROUND(distancia,0) > 200 AND ROUND(distancia,0) <= 600 AND ROUND(disponible_hilos) > 3)
                                ORDER BY distancia ASC
                                )ttt
							HAVING flg_cruce = 0
							ORDER BY distancia ASC
							limit 1
                        )tttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getReservasByCoordV2($lat, $long, $flg_edif)
    {
        $sql = "  SELECT codigo, distancia, latitud, longitud, hilos_disponibles, id_terminal
                    FROM (    
                        SELECT  codigo,
								ROUND(distancia,0) distancia, latitud, longitud, hilos_disponibles, id_terminal
                          FROM 
                            (
                            SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, hilos_disponibles, id_terminal
                                FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud, longitud,
                                                hilos_disponibles,
												id_terminal
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                        radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                        radians(" . $long . ") lonTo,
                                                        
                                                        radians(" . $lat . ") as  latTo,
                                                        codigo,
                                                        latitud, longitud,
                                                        hilos_disponibles,
														id_terminal
                                                FROM reservas_ubicacion
                                                )t
                                    )tt
								HAVING CASE WHEN ? = 1 THEN (ROUND(distancia,0) <= 800 AND hilos_disponibles > 8) 
                                            ELSE (ROUND(distancia,0) <= 800 AND hilos_disponibles > 3) END
                                ORDER BY distancia ASC
                                limit 1
                                )ttt
                        )tttt";
        $result = $this->db->query($sql, array($flg_edif));
        return $result->row_array();
    }

    function getReservasByCoordSimuV2($lat, $long, $flg_edif)
    {
        $sql = "  SELECT codigo, distancia, latitud, longitud, hilos_disponibles, id_terminal
                    FROM (    
                        SELECT  codigo,
								ROUND(distancia,0) distancia, latitud, longitud, hilos_disponibles, id_terminal, flg_cruce
                          FROM 
                            (
                            SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, hilos_disponibles, id_terminal,
									(SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
														  GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) lg
									   FROM vias_metropolitanas_ubicacion
									   ORDER BY lg DESC
									   limit 1) flg_cruce
							  FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud, longitud,
                                                hilos_disponibles,
												id_terminal
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                        radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                        radians(" . $long . ") lonTo,
                                                        
                                                        radians(" . $lat . ") as  latTo,
                                                        codigo,
                                                        latitud, longitud,
                                                        hilos_disponibles,
														id_terminal
                                                FROM reservas_ubicacion
                                                )t
                                    )tt
									GROUP BY id_terminal
									HAVING CASE WHEN ? = 1 THEN (ROUND(distancia,0) <= 800 AND hilos_disponibles > 8) 
												ELSE (ROUND(distancia,0) <= 800 AND hilos_disponibles > 3) END
                                )ttt
								HAVING flg_cruce = 0
								ORDER BY distancia ASC
                                limit 1
                        )tttt";
        $result = $this->db->query($sql, array($flg_edif));
        return $result->row_array();
    }

    function getEbcByCoordV2($lat, $long)
    {
        $sql = "  SELECT codigo, distancia, latitud, longitud
                    FROM (    
                        SELECT CASE WHEN  ROUND(distancia,0) <= 1500 THEN codigo 
                                    ELSE NULL END codigo,
                                    ROUND(distancia,0) distancia, latitud, longitud
                            FROM 
                            (
                            SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud
                                FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud,
                                                longitud
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                       radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                       radians(" . $long . ") lonTo,                                                        
                                                       radians(" . $lat . ") as  latTo,
                                                       codigo,
                                                       latitud,
                                                       longitud
                                                  FROM ebc_ubicacion
                                                )t
                                    )tt
                                ORDER BY distancia ASC
                                limit 1
                            )ttt
                        )tttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getEbcByCoordSimuV2($lat, $long)
    {
        $sql = "  SELECT codigo, distancia, latitud, longitud
                    FROM (    
                        SELECT CASE WHEN  ROUND(distancia,0) <= 1500 THEN codigo 
                                    ELSE NULL END codigo,
                                    ROUND(distancia,0) distancia, latitud, longitud, flg_cruce
                            FROM 
                            (
                            SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, id,
									(SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
														  GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) lg
									   FROM vias_metropolitanas_ubicacion
									   ORDER BY lg DESC
									   limit 1) flg_cruce
                                FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud,
                                                longitud,
												id
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                       radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                       radians(" . $long . ") lonTo,                                                        
                                                       radians(" . $lat . ") as  latTo,
                                                       codigo,
                                                       latitud,
                                                       longitud,
													   id
                                                  FROM ebc_ubicacion
                                                )t
                                    )tt
								GROUP BY id	
                                ORDER BY distancia ASC
                                limit 100
                            )ttt
							HAVING flg_cruce = 0
							ORDER BY distancia ASC
							limit 1
                        )tttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getDistanciaEbcCodigo($lat, $long)
    {
        $sql = "SELECT ROUND((atan2(sqrt(a), b))*6371000,0) as distancia, codigo, latitud, longitud
                                FROM (
                                        SELECT pow(cos(latTo) * sin(lonDelta), 2) +
                                                pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
                                                sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
                                                codigo,
                                                latitud,
                                                longitud
                                        FROM (
                                                SELECT radians(latitud)  AS latFrom,
                                                       radians(" . $long . ") - radians(longitud) AS lonDelta,
                                                       radians(" . $long . ") lonTo,                                                        
                                                       radians(" . $lat . ") as  latTo,
                                                       codigo,
                                                       latitud,
                                                       longitud
                                                  FROM ebc_ubicacion
                                                )t
                                    )tt
                                ORDER BY distancia ASC
                                limit 1";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getPlanobraClusterAll()
    {
        $sql = "SELECT pc.*, pq.codigo as mdf
		          FROM planobra_cluster pc, 
					   pqt_central pq
				 WHERE DATE(fecha_registro) >= '2019-11-18' 
				   AND pq.idCentral = pc.idCentral
                   AND flg_robot = 1
                   AND pc.latitud <> ''
                   AND estado <> 3
				   AND clasificacion = 'Estudio Especial Gris'
                   AND estado = 0";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function update_cotizacion_fac_red($arrayCotizacionUpdate)
    {
        $this->db->update_batch('planobra_cluster', $arrayCotizacionUpdate, 'id_planobra_cluster');
    }

    function getCountPo($itemplan, $idEstacion, $area = 1)
    {
        $sql = "SELECT COUNT(1) count 
		          FROM planobra_po 
				 WHERE itemplan   = COALESCE(?, itemplan) 
				   AND idEstacion = COALESCE(?, idEstacion)
				   AND flg_tipo_area = ?
				   AND estado_po NOT IN (7,8)";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $area));
        return $result->row_array()['count'];
    }

    function getCountPoSinAnclas($itemplan)
    {
        $sql = "SELECT COUNT(1) count 
		          FROM planobra_po 
				 WHERE itemplan   = COALESCE(?, itemplan) 
				   AND CASE WHEN idEstacion IN(2,5) THEN false ELSE true END
				   AND flg_tipo_area = 1
				   AND estado_po NOT IN (7,8)";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
    }

    function deleteDataLicencia($itemplan)
    {
        $sql = "DELETE ie,ppo,dp,ppod
                  FROM itemplan_estacion_licencia_det ie 
             LEFT JOIN (planobra_po ppo, 
                        detalleplan dp, 
                        planobra_po_detalle_partida ppod)
                    ON (ppo.itemplan = ie.itemplan 
                        AND ppo.idEstacion = 20 
                        AND ppo.estado_po NOT IN (5,6)
                        AND dp.poCod = ppo.codigo_po
                        AND ppod.codigo_po = ppo.codigo_po)
                  WHERE ie.itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));

        if ($this->db->trans_status() === TRUE) {
            $sql2 = "UPDATE pre_diseno 
					   SET requiere_licencia = 2 
				     WHERE itemplan = ?";
            $result = $this->db->query($sql2, array($itemplan));

            return 1;
        } else {
            return 2;
        }
    }

    function canCreatePoMat($itemplan)
    { #VALIDACION PEDIDO DE OWEN 13.01.2020 no crear po mat obras menores al 17 nov 2019
        $sql = "SELECT  count(1) as cant
	            FROM    planobra 
                WHERE   fecha_creacion <= '2019-11-17'
                AND	    itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['cant'];
    }

    function getDataAllTramaSisego($flgTipo, $flg_exito)
    {
        $sql = "SELECT origen,
                       ptr,
                       fecha_registro,
                       motivo_error,
                       UPPER(descripcion) descripcion,
                       estado,
                       flg_tipo,
                       itemplan,
                       CASE WHEN flg_tipo = 3 THEN 'COTIZACION (po->sw)' END AS situacion
                  FROM log_tramas_sigoplus
                 WHERE flg_tipo = COALESCE(?, flg_tipo)
                   AND estado   = COALESCE(?, estado)";
        $result = $this->db->query($sql, array($flgTipo, $flg_exito));
        return $result->result_array();
    }

    public function getExpedientesInfo($itemplan, $idEstacion)
    {

        $sql = "SELECT SUM(CASE WHEN estado = 'ACTIVO' THEN 1 ELSE 0 END) as has_activo,
                       SUM(CASE WHEN estado = 'DEVUELTO' THEN 1 ELSE 0 END) as has_devuelto
                FROM    itemplan_expediente
                WHERE   itemplan  = ?
                AND     idEstacion = ?
                LIMIT   1";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array();
    }

    function getCountPepBianualByItemplan($itemplan)
    {
        $sql = "SELECT COUNT(1) count
				  FROM planobra_po ppo, pep_bianual pb 
				 WHERE ppo.pep1 = pb.pep
				   AND ppo.itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
    }

    public function itemplanExecptionCreatePoMat($itemplan)
    { #VALIDACION PEDIDO DE OWEN 13.01.2020 no crear po mat obras menores al 17 nov 2019
        $sql = "SELECT count(1) as cant FROM itemplan_create_po_mat WHERE itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['cant'];
    }

    function countParalizados_v2($itemplan, $flg, $origen)
    {
        $sql = "SELECT COUNT(1) count, idMotivo
				  FROM paralizacion
				 WHERE itemplan = '" . $itemplan . "'
				   AND flg_activo = " . $flg;
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getPorcentajeByItemplanAndEstacion($itemplan, $idEstacion)
    {
        $sql = "SELECT COUNT(1) count
				  FROM itemplanestacionavance
                 WHERE idEstacion = ?
                   AND itemplan   = ?
                   AND porcentaje = 100";
        $result = $this->db->query($sql, array($idEstacion, $itemplan));
        return $result->row_array()['count'];
    }

    function countFichaTecnicaByItemplanAndEstacion($itemplan, $idEstacion)
    {
        $sql = "SELECT COUNT(1)as count
				  FROM ficha_tecnica 
				 WHERE itemplan    = ?
				   AND id_estacion = ?";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array()['count'];
    }

    /**
     * @param tipoArea MO = 2 o MAT = 1
     * @return datos necearios para validacion de creacion de po mo y mat
     */
    function getVariablesCostoUnitario($itemplan, $tipoArea, $codigo_po)
    {
        $Query = "SELECT po.itemplan, po.costo_unitario_mat, po.costo_unitario_mo, tb.total
                    FROM planobra po    LEFT JOIN (SELECT ppo.itemplan, SUM(costo_total) AS total FROM planobra_po ppo
                                                    WHERE ppo.itemplan = ? 
                                                    AND ppo.estado_po NOT IN (" . PO_PRECANCELADO . "," . PO_CANCELADO . ")
                                                    AND ppo.flg_tipo_area = ?
                                                    AND ppo.codigo_po not in (?)
                                                    GROUP BY ppo.itemplan) AS tb
                                        ON po.itemplan = tb.itemplan
                    WHERE po.itemplan = ?
                    LIMIT 1;";
        $result = $this->db->query($Query, array($itemplan, $tipoArea, (($codigo_po != null) ? $codigo_po : 0), $itemplan));
        log_message('error', $this->db->last_query());
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    public function hasSolExceActivo($itemplan, $tipo_po)
    {
        $sql = "SELECT  count(1) as cant
                FROM    solicitud_exceso_obra 
                WHERE   itemplan = ?
                #AND     tipo_po = ?
                AND     estado_valida IS NULL";
        $result = $this->db->query($sql, array($itemplan, $tipo_po));
        _log($this->db->last_query());
        return $result->row_array()['cant'];
    }

    function getCountSisegoParalizacionExitosa($itemplan)
    {
        $sql = "SELECT COUNT(1) count
				  FROM sisegos_no_paralizar_automatico
				 WHERE itemplan = ? ";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
    }

    function getFaseByItemplan($itemplan)
    {
        $sql = "SELECT faseDesc 
                  FROM planobra po,
				       fase f
                 WHERE itemplan = ?
				   AND f.idFase = po.idFase";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['faseDesc'];
    }

    function getPlanObraPoByCodigoPo($po)
    {
        $sql = "SELECT codigo_po, 
                       pep1,
                       pep2,
                       grafo,
                       itemplan
                  FROM planobra_po 
                 WHERE codigo_po = ?";
        $result = $this->db->query($sql, array($po));
        return $result->row_array();
    }

    function getCountCotizacionByCod($codigo_cluster, $estado = null)
    {
        $sql = "SELECT COUNT(1) count
                  FROM planobra_cluster 
                 WHERE codigo_cluster = ?
				   AND estado         = COALESCE(?,estado)";
        $result = $this->db->query($sql, array($codigo_cluster, $estado));
        return $result->row_array()['count'];
    }

    function getCountSisegoParaliza($sisego)
    {
        $sql = "SELECT COUNT(1) count
                  FROM sisego_paralizacion 
                 WHERE sisego = ?
				   AND estado = 1";
        $result = $this->db->query($sql, array($sisego));
        return $result->row_array()['count'];
    }

    function getDataPlanificacionItem($idSubProyecto, $idFase)
    {
        $sql = "SELECT s.idSubProyecto,
                       s.idFase,
                       s.id_mes,
                       s.nombre_plan,
                       s.cantidad,
                       m.nombre as nombreMes,
					   s.id_plan
                  FROM subproyecto_fases_cant_item_planificacion s,
                       mes m
                 WHERE m.id_mes        = s.id_mes
                   AND s.idSubProyecto = COALESCE(?, s.idSubProyecto)
                   AND s.idFase        = COALESCE(?, s.idFase)";
        $result = $this->db->query($sql, array($idSubProyecto, $idFase));
        return $result->result_array();
    }

    function getMesAll()
    {
        $sql = "SELECT id_mes,
                       nombre,
                       abrev
                  FROM mes";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function cantidadPlanTotal($idSubProyecto, $idFase)
    {
        $sql = " SELECT ROUND(SUM(s.cantidad))totalCantidad
                   FROM subproyecto_fases_cant_item_planificacion s,
                        mes m
                  WHERE m.id_mes        = s.id_mes
                    AND s.idSubProyecto = COALESCE(?, s.idSubProyecto)
                    AND s.idFase        = COALESCE(?, s.idFase) ";
        $result = $this->db->query($sql, array($idSubProyecto, $idFase));
        return $result->row_array()['totalCantidad'];
    }

    function getIdFaseByItemplan($itemplan)
    {
        $sql = "SELECT f.idFase 
                  FROM planobra po,
				       fase f
                 WHERE itemplan = ?
				   AND f.idFase = po.idFase";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['idFase'];
    }

    function getCountPresupuesto($pep1, $codigo_coti)
    {
        $sql = "SELECT COUNT(1) count
				  FROM sap_detalle
				 WHERE pep1 = ?
				   AND ROUND(monto_temporal,2) >= (SELECT ROUND(costo_total,2)
													  FROM planobra_cluster pc
												     WHERE codigo_cluster = ?
												    limit 1)";
        $result = $this->db->query($sql, array($pep1, $codigo_coti));
        return $result->row_array()['count'];
    }

    function getCountExistPep($pep1)
    {
        $sql = "SELECT COUNT(1) count
				  FROM sap_detalle
				 WHERE pep1 = ?";
        $result = $this->db->query($sql, array($pep1));
        return $result->row_array()['count'];
    }

    function getCountMismoTipoCoti($codigo, $indicador)
    {
        $sql = "  SELECT COUNT(1) count
					FROM planobra_cluster pc
				   WHERE pc.codigo_cluster = COALESCE(?, pc.codigo_cluster)
					 AND CONCAT(pc.sisego,'-',pc.flg_principal) = ? ";
        $result = $this->db->query($sql, array($codigo, $indicador));
        return $result->row_array()['count'];
    }

    function getCountCotiAsociadoItem($codigo)
    {
        $sql = "  SELECT COUNT(1) AS count
					FROM planobra_cluster pc,
					     planobra po
				   WHERE pc.itemplan = po.itemplan
				     AND codigo_cluster = ?
					 AND po.idEstadoPlan NOT IN(6,10)
					 AND pc.itemplan IS NOT NULL";
        $result = $this->db->query($sql, array($codigo));
        return $result->row_array()['count'];
    }

    function getDataCrearCotiByItemplan()
    {
        $sql = "SELECT itemplan,
					   substring_index(indicador,'-',3)as sisego,
					   CASE WHEN substring_index(indicador,'-',-1) NOT IN (0,1) THEN 0 
					        ELSE substring_index(indicador,'-',-1) END flg_principal,
					   coordX,
					   coordY,
					   tendido_externo,
					   acceso_cliente,
					   tendido_externo,
					   nombre_estudio,
					   UPPER(tipo_sede) AS tipo_cliente,
					   operador AS clasificacion,
					   idCentral
				  FROM planobra WHERE itemplan IN ('')
			AND coordY <> ''
            AND coordY IS NOT NULL";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function insertRobotCotizacionByItemplan($arrayCotizacionUpdate, $arrayLog)
    {
        $this->db->trans_begin();

        $this->db->insert_batch('planobra_cluster', $arrayCotizacionUpdate);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->insert_batch('log_planobra_cotizacion_sisego', $arrayLog);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }
    }

    function getCountExistSisego($sisego)
    {
        $sql = "SELECT COUNT(1) count
				  FROM planobra 
				 WHERE indicador = ?";
        $result = $this->db->query($sql, array($sisego));
        return $result->row_array()['count'];
    }

    function actualizarMontoDisponible($pep1, $codigo_cluster)
    {
        $sql = "UPDATE sap_detalle
				   SET monto_temporal = ROUND(monto_temporal - ( SELECT ROUND(costo_total,2)
																	   FROM planobra_cluster pc
																	  WHERE codigo_cluster = ?
																	 limit 1), 2)
				 WHERE pep1 = ?";
        $result = $this->db->query($sql, array($codigo_cluster, $pep1));
        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'ERROR AL ACTUALIZAR EL PRESUPUESTO.';
        } else {
            $data['error'] = EXIT_SUCCESS;
        }

        return $data;
    }

    function isSisego($itemplan)
    {
        $Query = "SELECT    COUNT(1) as count  
                    FROM    planobra 
                   WHERE    itemplan = ?
					AND		idSubProyecto in (13,14,15)";
        $result = $this->db->query($Query, array($itemplan));
        return $result->row()->count;
    }

    function getCodigoPOItemfault($item)
    {
        $Query = "SELECT getPoCodItemfault(?) as codigoPO";
        $result = $this->db->query($Query, array($item));
        if ($result->row() != null) {
            return $result->row_array()['codigoPO'];
        } else {
            return null;
        }
    }

    function getCountSolicitudCancelacion($itemplan)
    {
        $sql = "SELECT COUNT(1) count 
		          FROM planobra 
                 WHERE flgSolicitudCancelacion = 0
				   AND itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
    }

    function crearOcSisegoByItemplan($itemplan, $pep2 = null)
    {
        $sql = "SELECT createOCSisegosByItemplan(?, ?) valid";
        $result = $this->db->query($sql, array($itemplan, $pep2));
        return $result->row_array()['valid'];
    }

    function getDataCotizacionCostos($codigo_cluster)
    {
        $sql = "SELECT  ROUND(COALESCE(costo_materiales, 0)+
							  COALESCE(costo_mat_edif, 0)+
							  COALESCE(costo_oc_edif, 0), 2)costo_materiales,	
					   ROUND( COALESCE(costo_mano_obra, 0)+
							  COALESCE(costo_diseno, 0)+
							  COALESCE(costo_expe_seia_cira_pam, 0)+
							  COALESCE(costo_adicional_rural, 0)+
							  COALESCE(costo_oc, 0), 2)costo_mo, 
					   costo_total 
				  FROM planobra_cluster 
				 WHERE codigo_cluster = ?";
        $result = $this->db->query($sql, array($codigo_cluster));
        return $result->row_array();
    }

    function getDataLogHojaGestion($hoja_gestion)
    {
        $sql = "SELECT lg.hoja_gestion,
					   lg.ptr,
					   u.nombre,
					   lg.fecha_remove
				  FROM log_remove_ptr_hg lg,
					   usuario u
				 WHERE u.id_usuario = lg.usuario_remove
				   AND lg.hoja_gestion = COALESCE(?, lg.hoja_gestion)";
        $result = $this->db->query($sql, array($hoja_gestion));
        return $result->result_array();
    }

    //////////////////////* NEW CC */////////////////////////////////////////////////////////////////////////////
    function getSubProyectoByTipo($idTipoSub)
    {
        $sql = "SELECT idSubProyecto,
                       subProyectoDesc 
                  FROM subproyecto
                 WHERE idTipoSubProyecto = ?";
        $result = $this->db->query($sql, array($idTipoSub));
        return $result->result_array();
    }

    function getEECCbuclePaque($idCentral)
    {
        $query = "SELECT DISTINCT ec.idEmpresaColab, 
                         ec.empresaColabDesc, 
                         ec.flg_trabajo AS flgTipoSubProyecto
                    FROM pqt_central c, empresacolab ec
                   WHERE c.idEmpresaColab = ec.idEmpresaColab
                     AND c.idCentral = COALESCE(?, c.idCentral)";
        $result = $this->db->query($query, array($idCentral));
        return $result->result();
    }

    function getEECCbucleNoPaque($idCentral)
    {
        $query = "SELECT DISTINCT ec.idEmpresaColab, 
                         ec.empresaColabDesc, 
                         ec.flg_trabajo AS flgTipoSubProyecto
                    FROM central c, empresacolab ec
                   WHERE c.idEmpresaColab = ec.idEmpresaColab
                     AND c.idCentral = ?";
        $result = $this->db->query($query, array($idCentral));
        return $result->result();
    }

    function getEECCIntegral($idCentral)
    {
        $query = "SELECT DISTINCT ec.idEmpresaColab, 
                         ec.empresaColabDesc, 
                         ec.flg_trabajo AS flgTipoSubProyecto
                    FROM pqt_central c, empresacolab ec
                   WHERE c.idEmpresaColabCV = ec.idEmpresaColab
                     AND c.idCentral = COALESCE(?, c.idCentral)";
        $result = $this->db->query($query, array($idCentral));
        return $result->result();
    }

    function getDataSubProyectoById($idSubProyecto)
    {
        $sql = "SELECT idSubProyecto,
                       subProyectoDesc,
                       costo_unitario_mat,
                       costo_unitario_mo,
					   paquetizado_fg,
                       flg_reg_item_capex_opex,
                       idTipoPlanta,
                       flg_opex
                  FROM subproyecto 
                 WHERE idSubProyecto = ?";
        $result = $this->db->query($sql, array($idSubProyecto));
        return $result->row_array();
    }

    function generarSolicitudOC($id_plan, $itemplan = null, $costo_total = null)
    {
        $sql = "SELECT fn_create_solicitud_oc(?, ?, ?) AS flgValida";
        $result = $this->db->query($sql, array($id_plan, $itemplan, $costo_total));
        return $result->row_array()['flgValida'];
    }

    function getPlanobraAll($itemplan, $idSubProyecto, $idEmpresaColab)
    {
        $sql = "SELECT po.itemplan,
                       po.fecha_creacion,
                       s.idSubProyecto,
                       s.subProyectoDesc,
                       e.empresaColabDesc
		          FROM planobra po, 
					   subproyecto s,
                       empresaColab e
                 WHERE po.idSubProyecto = s.idSubProyecto
                   AND e.idEmpresaColab = po.idEmpresaColab
                   AND po.itemplan      = COALESCE(?, po.itemplan)
                   AND po.idSubPRoyecto = COALESCE(?, po.idSubProyecto)
                   AND e.idEmpresaColab = COALESCE(?, po.idEmpresaColab)";
        $result = $this->db->query($sql, array($itemplan, $idSubProyecto, $idEmpresaColab));
        return $result->result_array();
    }

    function getIdSubProyectoByIdPLan($id_plan)
    {
        $sql = "SELECT *
                  FROM subproyecto_fases_cant_item_planificacion 
                 WHERE id_plan = ?
                 limit 1";
        $result = $this->db->query($sql, array($id_plan));
        return $result->row_array();
    }

    function getFaseAll()
    {
        $sql = "SELECT * 
                  FROM fase
				 WHERe estado = 1";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getDataCuotasBySubProyecto($faseDesc, $idSubProyecto)
    {
        $sql = "SELECT cantItemPlan
                  FROM subproyecto_fases_cant_itemplan
                 WHERE idSubProyecto = ?
                   AND fase = ?";
        $result = $this->db->query($sql, array($idSubProyecto, $faseDesc));
        return $result->row_array()['cantItemPlan'];
    }

    function getFlgPorcAbrePue($id_plan)
    {
        $sql = "  SELECT CASE WHEN cantidad = countPorAbrePue THEN 1
                              ELSE 0 END flg_abrePue_por
                    FROM ( 
                            SELECT sp.nombre_plan, 
                                   sp.cantidad, 
                                   COUNT(1) countItems,
                                   CASE WHEN pv.estado_aprob = 1 THEN SUM(1)
                                        ELSE 0 END countPorAbrePue
                            FROM (subproyecto_fases_cant_item_planificacion sp, planobra po)
                        LEFT JOIN planobra_detalle_cv pv ON po.itemplan = pv.itemplan
                            WHERE sp.id_plan = ?
                                AND po.id_plan = sp.id_plan
                            GROUP BY po.id_plan
                        )t";
        $result = $this->db->query($sql, array($id_plan));
        return $result->row_array()['flg_abrePue_por'];
    }

    //////////////////////////////////////////////////////////////////////////////

    function getDataEnvioCotiMasivo()
    {
        $sql = "SELECT  codigo_cluster as codigo,
						costo_materiales as materiales,
						c.codigo as nodo,
						(COALESCE(costo_mano_obra, 0)+COALESCE(costo_expe_seia_cira_pam,0)+COALESCE(costo_adicional_rural,0)+
                         COALESCE(costo_oc,0)) as mano_obra,
						duracion,
						id_tipo_diseno as tipo_diseno,
						costo_diseno as diseno,
                        null as comentario,
						CASE WHEN tipo = 'EBC' THEN facilidades_de_red
						     ELSE 'xxxx' END ebc
		          FROM planobra_cluster pc,
                       pqt_central c
				 WHERE c.idCentral = pc.idCentral
				   AND codigo_cluster IN ('CL-613354')";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function envioCotizacionMasivo()
    {
        $dataArrayTrama = $this->getDataEnvioCotiMasivo();

        foreach ($dataArrayTrama as $rowTramaArray) {
            _log("codigo: " . $rowTramaArray['codigo']);
            $dataSend = $rowTramaArray;
            $url = 'https://172.30.5.10:8080/sisego/Requerimientos/cotizarEstudio';

            $response = $this->m_utils->sendDataToURL($url, $dataSend);

            if ($response) {
                if ($response->error == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL', $rowTramaArray['codigo'], NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3, $response);
                } else {
                    $data['error'] = EXIT_ERROR;
                    $this->db->trans_rollback();
                    $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL', $rowTramaArray['codigo'], NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 3, $response);
                }
            } else {
                $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL', $rowTramaArray['codigo'], NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', 'GICS NO ENVIO RESPUESTA', 2, 3, $response);
            }
        }
    }

    function getFlgDespSisego($itemplan)
    {
        $sql = "SELECT COUNT(1) count
				  FROM planobra
			     WHERE itemplan = ?
				   AND motivo_paralizado IN (11,66,70)";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
    }

    function insertEvaluaPep($arrayData)
    {
        $this->db->insert('evalua_nuevo_pep', $arrayData);

        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
        } else {
            $data['error'] = EXIT_SUCCESS;
        }
        return $data;
    }

    function getDataEvaluaPeps()
    {
        $sql = "SELECT eva.*, po.itemplan
				  FROM evalua_nuevo_pep eva
			 LEFT JOIN planobra po ON eva.sisego = po.indicador
			 LEFT JOIN sap_detalle sap ON sap.pep1 = eva.pep
			     WHERE sap.pep1 IS NOT NULL
			       AND po.itemplan IS NULL";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    /*     * metodos paquetizados czavala 20.04.2020* */

    function getInfoEstacionByIdEstacion($idEstacion)
    {
        $Query = "SELECT * FROM estacion where idEstacion = ?";
        $result = $this->db->query($Query, array($idEstacion));
        return $result;
    }

    function getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacion)
    {
        $Query = "SELECT * FROM itemplanestacionavance 
                    WHERE itemplan = ?
                    AND idEstacion = COALESCE(?, idEstacion)
                    LIMIT 1";
        $result = $this->db->query($Query, array($itemplan, $idEstacion));
        return $result->row_array();
    }

    function updateSisego($pep)
    {
        $sql = "UPDATE evalua_nuevo_pep SET flg_estado=1 WHERE pep='$pep'";
        $this->db->query($sql);
        if ($this->db->affected_rows() != 1) {
            throw new Exception('No se puede actualizar.');
        } else {
            return array("error" => EXIT_SUCCESS, "msj" => 'OPERACION REALIZADA CON EXITO');
        }
    }

    function getDataItemplanMadre($idProyecto, $idSubProyecto)
    {
        $sql = "SELECT * 
                  FROM itemplan_madre
                 WHERE idProyecto    = COALESCE(?, idProyecto)
                   AND idSubProyecto = COALESCE(?, idSubProyecto)
                   AND idEstado = 2";
        $result = $this->db->query($sql, array($idProyecto, $idSubProyecto));
        return $result->result_array();
    }

    function generarItemMadre()
    {
        $sql = "    SELECT CONCAT('M-',
                            DATE_FORMAT(CURDATE(),'%y'),'-',
                            CASE WHEN LENGTH(MONTH(NOW())) = 1 THEN CONCAT('0',MONTH(NOW()))
                                ELSE MONTH(NOW()) END,
                                CASE WHEN LENGTH( 
                                                    (  SELECT COUNT(1) 
                                                        FROM itemplan_madre
                                                        WHERE MONTH(fecha_registro) = MONTH(NOW())
                                                    )
                                                ) IN (0,1) THEN '-00'
                                        WHEN LENGTH( 
                                                    (  SELECT COUNT(1) 
                                                        FROM itemplan_madre
                                                        WHERE MONTH(fecha_registro) = MONTH(NOW())
                                                    )
                                                    
                                                    )  = 2 THEN '-0'
                                                    
                                        WHEN	LENGTH( 
                                                    (  SELECT COUNT(1) 
                                                        FROM itemplan_madre
                                                        WHERE MONTH(fecha_registro) = MONTH(NOW())
                                                    )
                                                    
                                                    ) = 3 THEN '-' END,
                                    (
                                        SELECT COUNT(1) 
                                        FROM itemplan_madre
                                        WHERE MONTH(fecha_registro) = MONTH(NOW())
                                    )
                            ) as itemplan_madre";
        $result = $this->db->query($sql);
        return $result->row_array()['itemplan_madre'];
    }

    function getPlanobraByItemplanMadre($itemplan)
    {
        $sql = "SELECT *
	              FROM itemplan_madre 
	             WHERE itemplan_m = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    /*     * fin metodos paquetizados* */

    function getDataRegistroSinCoti()
    {
        $sql = "SELECT itemPlan,
       substring_index(indicador,'-',3) sisego,
       substring_index(indicador,'-',-1) flg_principal,
       costo_unitario_mo_crea_oc,
       costo_unitario_mat,
       (costo_unitario_mat +costo_unitario_mo_crea_oc ) costo_total,
       CASE WHEN paquetizado_fg = 2 THEN idCentralPqt ELSE idCentral END idCentral,
       paquetizado_fg,
	   curdate() AS fecha_creacion,
	   idSubProyecto
  FROM planobra WHERE itemplan IN ('18-0310900969',
'20-0321500079',
'20-0321500080',
'20-0321500082')";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getAllCto()
    {
        $sql = "SELECT codigo,
                       ubicacion_cto,
                       ct.latitud,
                       ct.longitud,
                       ct.tecnologia,
                       ct.segmento,
                       ct.ocupacion_hilos,
                       total_hilos,
					   SUM(CASE WHEN estado = 1 THEN 1 
                            ELSE 0 END) cant_coti_pen,
					   SUM(CASE WHEN estado = 2 THEN 1 
                            ELSE 0 END) cant_coti_aprob,
					   SUM(CASE WHEN estado = 4 THEN 1 
                            ELSE 0 END) cant_coti_pen_conf,
					   SUM(CASE WHEN estado <> 3 THEN 1 
                            ELSE 0 END) cant_coti_total,
                       (total_hilos-ocupacion_hilos) as hilos_disponibles,
					   ubicacion_cto
                  FROM cto_ubicacion ct 
			 LEFT JOIN planobra_cluster ON (facilidades_de_red = ct.codigo AND estado <> 3)
             GROUP BY ct.codigo";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getAllReservas()
    {
        $sql = " SELECT id_terminal,
		                codigo,
                        ubicacion_reserva,
                        latitud,
                        longitud,
                        cable,
                        COALESCE(hilos_disponibles, 0) AS hilos_disponibles
                   FROM reservas_ubicacion";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getAllEbc()
    {
        $sql = " SELECT codigo,
                        latitud,
                        longitud,
                        direccion,
                        nom_estacion
                   FROM ebc_ubicacion";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getAllProyectoByNotId()
    {

        $Query = "  SELECT p.*
	                FROM proyecto p, subproyecto s
                    WHERE p.idProyecto = s.idProyecto
                      AND s.paquetizado_fg IN (1,2)
					  AND s.idTipoPlanta in (1)
	                  AND p.idProyecto not in (21,4)
                      GROUP BY p.idProyecto
	                ORDER BY proyectoDesc";
        /* $Query = "  SELECT *
          FROM proyecto
          WHERE NOT idProyecto in (21)
          ORDER BY proyectoDesc" ; */

        $result = $this->db->query($Query, array());
        return $result;
    }

    function getDataCotizacionByCod($codigo_coti)
    {
        $sql = "SELECT c.*, 
					   pc.distancia_lineal + (pc.distancia_lineal*0.30) distancia,
					   UPPER(t.descripcion) AS tipo_diseno_desc, t.id_tipo_diseno,
					   CASE WHEN (pc.metro_tendido_aereo + pc.metro_tendido_subterraneo) > 5000 AND flg_robot = 2 THEN 1
							WHEN t.id_tipo_diseno  IN (4, 8) THEN 1 
							ELSE 2 END flg_paquetizado
				  FROM planobra_cluster pc, pqt_central c, tipo_diseno t
				 WHERE codigo_cluster = ?
				   AND pc.idCentral = c.idCentral
				   AND t.id_tipo_diseno = pc.id_tipo_diseno";
        $result = $this->db->query($sql, array($codigo_coti));
        return $result->row_array();
    }

    function getInfoSubProyectoByIdSubProyecto($idSubProyecto)
    {
        $Query = "SELECT * 
	               FROM subproyecto 
	               WHERE idSubProyecto = ?";
        $result = $this->db->query($Query, array($idSubProyecto));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getCentralPqt($idCentral)
    {
        $sql = "	SELECT c.idCentral,
                       c.codigo,
                       c.idZonal,
                       c.idEmpresaColab,
                       c.jefatura,
                       c.region,
                       c.flg_tipo_zona,
                       c.latitud,
                       c.longitud,
                       e.empresaColabDesc
		          FROM pqt_central c,
                       empresacolab e
				 WHERE c.idEmpresaColab = e.idEmpresaColab
				   AND c.idCentral = COALESCE(?, c.idCentral)";
        $result = $this->db->query($sql, array($idCentral));
        return $result->row_array();
    }

    function getAllCtoEdificios()
    {
        $sql = "SELECT codigo,
                       ubicacion_cto,
                       ct.latitud,
                       ct.longitud,
                       ct.tecnologia,
                       ct.ocupacion_hilos,
                       total_hilos,
					   SUM(CASE WHEN estado = 1 THEN 1 
                            ELSE 0 END) cant_coti_pen,
					   SUM(CASE WHEN estado = 2 THEN 1 
                            ELSE 0 END) cant_coti_aprob,
					   SUM(CASE WHEN estado = 4 THEN 1 
                            ELSE 0 END) cant_coti_pen_conf,
					   SUM(CASE WHEN estado <> 3 THEN 1 
                            ELSE 0 END) cant_coti_total,
                       (total_hilos-ocupacion_hilos) as hilos_disponibles,
					   ct.tipo_pa
                  FROM cto_ubicacion_edificio ct 
			 LEFT JOIN planobra_cluster ON (facilidades_de_red = ct.codigo AND estado <> 3)
             GROUP BY ct.codigo";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getCtoByCoordEdif($lat, $long)
    {
        $sql = "SELECT codigo,
					   ROUND(distancia,0) as distancia,
					   latitud, longitud, disponible_hilos,
					   id_terminal
				  FROM 
					(
					SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_terminal
						FROM (
								SELECT pow(cos(latTo) * sin(lonDelta), 2) +
										pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
										sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
										codigo,
										latitud, longitud,
										disponible_hilos,
										id_terminal
								FROM (
										SELECT radians(latitud)  AS latFrom,
											   radians(" . $long . ") - radians(longitud) AS lonDelta,
											   radians(" . $long . ") lonTo,
											   id_terminal,
											   radians(" . $lat . ") as  latTo,
												codigo,
												latitud, longitud,
												total_hilos-ocupacion_hilos as disponible_hilos
										  FROM cto_ubicacion_edificio
										 WHERE FALSE -- POR AHORA NO SE TOMA UM, BORRAR CUANDO SE TOME
										)t
							)tt
						HAVING (ROUND(distancia,0) <= 50 AND disponible_hilos > 0) 
						ORDER BY distancia ASC
						limit 1
						)ttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getCtoByCoordEdifSimu($lat, $long)
    {
        $sql = "SELECT codigo,
					   ROUND(distancia,0) as distancia,
					   latitud, longitud, disponible_hilos,
					   id_terminal,
					   CASE WHEN tecnologia = 'GPON' OR (tecnologia = 'P2P' AND disponible_hilos = 0) THEN 1
					        ELSE 2 END flg_tipo_diseno,
					   tecnologia
				  FROM 
					(
					SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_terminal,tecnologia,tipo_pa
						FROM (
								SELECT pow(cos(latTo) * sin(lonDelta), 2) +
										pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
										sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
										codigo,
										latitud, longitud,
										disponible_hilos,
										id_terminal,
										tecnologia,
										tipo_pa
								FROM (
										SELECT radians(latitud)  AS latFrom,
											   radians(" . $long . ") - radians(longitud) AS lonDelta,
											   radians(" . $long . ") lonTo,
											   id_terminal,
											   radians(" . $lat . ") as  latTo,
												codigo,
												latitud, longitud,
												total_hilos-ocupacion_hilos as disponible_hilos,
												tecnologia,
												tipo_pa
										  FROM cto_ubicacion_edificio
										)t
							)tt
						HAVING (ROUND(distancia,0) <= 55 AND disponible_hilos > 0 AND tipo_pa IN ('0 - Site Holder','10 - Fachada','11 - Punto de acceso de edificio','4 - Punto de acceso genérico')
								AND tecnologia = 'P2P')
						ORDER BY distancia ASC
						limit 1
						)ttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getMdfCoord($lat, $long)
    {
        $sql = "SELECT ROUND((atan2(sqrt(a), b))*6371000,0) as distancia, codigo, latitud, longitud,idCentral
				  FROM (
						 SELECT pow(cos(latTo) * sin(lonDelta), 2) +
								pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
								sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
								codigo,
								latitud,
								longitud,
								idCentral
						  FROM (
								SELECT radians(latitud)  AS latFrom,
									   radians(" . $long . ") - radians(longitud) AS lonDelta,
									   radians(" . $long . ") lonTo,
									  
									   radians(" . $lat . ") as  latTo,
									   codigo,
									   latitud,
									   longitud,
									   idCentral
								  FROM pqt_central
								)t
					)tt
				ORDER BY distancia ASC
				limit 1";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getMdfCoordSimu($lat, $long)
    {
        $sql = "SELECT ttt.*, (SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
												    GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) lg
							     FROM vias_metropolitanas_ubicacion
							   ORDER BY lg DESC
							   limit 1) flg_cruce, idCentral
                  FROM  ( 
							SELECT ROUND((atan2(sqrt(a), b))*6371000,0) as distancia, codigo, latitud, longitud,idCentral
							  FROM (
									 SELECT pow(cos(latTo) * sin(lonDelta), 2) +
											pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
											sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
											codigo,
											latitud,
											longitud,
											idCentral
									  FROM (
											SELECT radians(latitud)  AS latFrom,
												   radians(" . $long . ") - radians(longitud) AS lonDelta,
												   radians(" . $long . ") lonTo,
												  
												   radians(" . $lat . ") as  latTo,
												   codigo,
												   latitud,
												   longitud,
												   idCentral
											  FROM pqt_central
											)t
									)tt
				ORDER BY distancia ASC
				limit 100
						)ttt
				GROUP BY idCentral
				HAVING flg_cruce = 0
                ORDER BY distancia ASC
				limit 1";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getMontoTemporalByPep1($pep1)
    {
        $sql = "SELECT CASE WHEN monto_temporal < 0 THEN 0 
		                    ELSE monto_temporal END monto_temporal
				  FROM sap_detalle
				 WHERE pep1 = ?";
        $result = $this->db->query($sql, array($pep1));
        if ($result->row() != null) {
            return $result->row_array()['monto_temporal'];
        } else {
            return null;
        }
    }

    function getTipoIncidentes($id_modulo)
    {
        $Query = "SELECT id_tipo_incidente, 
		                  descripcion, 
						  comentario, 
						  estado 
				     FROM gi_tipo_incidente 
					WHERE estado = 'A'
					  AND CASE WHEN ? = 14 THEN id_modulo = ?
						       ELSE id_modulo IS NULL END
				  ORDER BY descripcion";
        $result = $this->db->query($Query, array($id_modulo, $id_modulo));
        return $result->result_array();
    }

    function getCountNomEstudio($nom_estudio)
    {
        $sql = "SELECT COUNT(1) count
				  FROM nombre_estudio_cotizacion
				 WHERE UPPER(nombre_estudio) = UPPER(?)";
        $result = $this->db->query($sql, array($nom_estudio));
        return $result->row_array()['count'];
    }

    function getCountClasTendidoExt($clasificacion, $tendido_externo)
    {
        $sql = " SELECT COUNT(1) count 
				   FROM clasificacion_x_tendido_externo ct,
						clasificacion c,
						tendido_externo t
				  WHERE ct.id_clasificacion = c.id_clasificacion
				    AND t.id_tendido_externo = ct.id_tendido_externo
				    AND UPPER(c.nom_clasificacion)   = UPPER(?)
				    AND UPPER(t.nom_tendido_externo) = UPPER(?)";
        $result = $this->db->query($sql, array($clasificacion, $tendido_externo));
        return $result->row_array()['count'];
    }

    public function hasSolOCAtendido($itemplan)
    {
        $sql = "select count(1) as cant from planobra where itemplan = ? AND  solicitud_oc is not null and estado_sol_oc = 'ATENDIDO'";
        $result = $this->db->query($sql, array($itemplan));
        //_log($this->db->last_query());
        return $result->row_array()['cant'];
    }

    function getCodSolicitudOC()
    {
        $sql = "SELECT getNextCodigoSolicitudOC() as cod_solicitud";
        $result = $this->db->query($sql);
        return $result->row_array()['cod_solicitud'];
    }

    function getTipoPlantaByItemplan($itemplan)
    {
        $sql = "SELECT po.flg_opex
				  FROM planobra po, 
					   subproyecto s
				 WHERE po.idSubProyecto = s.idSubProyecto
				   AND po.itemplan      = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['flg_opex'];
    }

    /**nuevoo czavala 06.07.2020**/
    function getNextCodSolicitud()
    { //06.07.2020
        $Query = "SELECT getNextCodigoSolicitudOC() as codigoSolicitud";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->row_array()['codigoSolicitud'];
        } else {
            return null;
        }
    }

    function getCostoPqtForFactorMedicionObra($cantPlanificada, $idEstacion, $idSubProyecto, $idEmpresaColab, $tipoJefatura)
    {
        $Query = "SELECT
                    SUM(pbs.baremo * ? * pre.costo) AS costo_pqt
                    FROM
                    pqt_baremo_x_subpro_x_partida_mo pbs,
                    pqt_partidas_paquetizadas_x_estacion pqe,
                    pqt_tipo_preciario ptp,
                    pqt_preciario pre
                    WHERE
                    pbs.id_pqt_partida_mo_x_estacion    = pqe.id_tipo_partida
                    AND pqe.id_pqt_tipo_preciario       = ptp.id
                    AND ptp.id              = pre.idTipoPreciario
                    AND pqe.idEstacion      = ?
                    AND pbs.idSubProyecto   = ?
                    AND pre.idEmpresaColab  = ?
                    AND pre.tipoJefatura    = ?";
        $result = $this->db->query($Query, array($cantPlanificada, $idEstacion, $idSubProyecto, $idEmpresaColab, $tipoJefatura));
        if ($result->row() != null) {
            return $result->row_array()['costo_pqt'];
        } else {
            return null;
        }
    }

    function crearOCByMontoPqt($dataPlanobra, $solicitud_oc_creacion, $item_x_sol, $dataSapDetalle)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {

            $this->db->trans_begin();
            $this->db->where('itemplan', $dataPlanobra['itemplan']);
            $this->db->update('planobra', $dataPlanobra);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar en planobra.');
            } else {
                $this->db->insert('solicitud_orden_compra', $solicitud_oc_creacion);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en solicitud_orden_compra');
                } else {
                    $this->db->insert('itemplan_x_solicitud_oc', $item_x_sol);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
                    } else {
                        $this->db->where('pep1', $dataSapDetalle['pep1']);
                        $this->db->update('sap_detalle', $dataSapDetalle);
                        if ($this->db->trans_status() === FALSE) {
                            throw new Exception('Hubo un error al actualizar en sap_detalle.');
                        } else {
                            $data['error']    = EXIT_SUCCESS;
                            $data['msj']      = 'Se actualizo correctamente!';
                            $this->db->trans_commit();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function   getPEPSBolsaPepBySubProyecto($idSubProyecto)
    {
        $Query = "SELECT DISTINCT bp.pep1, sd.monto_temporal 
                    FROM subproyecto sp, bolsa_pep bp LEFT JOIN sap_detalle sd ON bp.pep1 = sd.pep1
                    WHERE  sp.idSubProyecto = bp.idSubProyecto
                    AND bp.tipo_pep IN (2,3) 
                    AND	bp.estado = 1
                    and sp.idSubProyecto = ?";
        $result = $this->db->query($Query, array($idSubProyecto));
        return $result->result();
    }

    function getCountPep($pep1)
    {
        $sql = "SELECT COUNT(1) count 
                  FROM evalua_nuevo_pep
			     WHERE pep = ?";
        $result = $this->db->query($sql, array($pep1));
        return $result->row_array()['count'];
    }

    function getOperador($itemplan)
    {
        $sql = "SELECT operador
	              FROM planobra 
	             WHERE itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['operador'];
    }

    function getTipoEntidad($estado)
    {
        $sql = "SELECT id_tipo_entidad,
					   nombre,
					   estado
				  FROM tipo_entidad
				  WHERE estado = ?";
        $result = $this->db->query($sql, array($estado));
        return $result->result_array();
    }

    function getEbcByDistrito($departamento)
    {
        $sql = "SELECT codigo,
                       nom_estacion
                  FROM ebc_ubicacion 
                 WHERE UPPER(departamento) = UPPER(?)";
        $result = $this->db->query($sql, array($departamento));
        _log($this->db->last_query());
        return $result->result_array();
    }

    function actualizarMontoDisponibleAll($pep1, $monto)
    {
        $sql = "UPDATE sap_detalle
				   SET monto_temporal = ROUND(monto_temporal - " . $monto . ", 2)
				 WHERE pep1 = ?";
        $result = $this->db->query($sql, array($pep1));
        _log($this->db->last_query());
        if ($this->db->trans_status() === FALSE) {
            // if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'NO SE COMPLETO LA TRANSACCION, INTENTE NUEVAMENTE.';
        } else {
            $data['error'] = EXIT_SUCCESS;
        }

        return $data;
    }

    function getDataPoByItemplan($itemplan)
    {
        $sql = " SELECT pt.ptr, 
                        FORMAT(SUM(total), 3) total,
                        ROUND(SUM(total), 3) costo_mo,
                        po.itemplan,
                        po.costo_unitario_mo_crea_oc,
                        CASE WHEN ROUND(SUM(total), 3) <> ROUND(po.costo_unitario_mo, 3) THEN 1 
                             ELSE 0 END flg_solicitud_edic
                   FROM ptr_x_actividades_x_zonal pt,
                        planobra po,
						ptr_planta_interna pp
                  WHERE pt.itemplan = po.itemplan
					AND pt.ptr = pp.ptr
					AND pp.rangoPtr != 6
                    AND pt.itemplan = '" . $itemplan . "'
                GROUP BY ptr";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getDataCentralPqtById($idCentral)
    {
        $sql = "SELECT idCentral, 
                       idTipoCentral, 
                       jefatura, 
                       idJefatura,
                       flg_tipo_zona,
                       idEmpresaColab,
                       distrito,
					   departamento
                  FROM pqt_central 
                 WHERE idCentral = ?";
        $result = $this->db->query($sql, array($idCentral));
        return $result->row_array();
    }
    /**termino czavala 06.07.2020**/

    function hasPoPqtActive($itemplan, $idEstacion)
    {
        $sql = "SELECT COUNT(1) count
                  FROM planobra_po
			     WHERE itemplan = ?
	             AND   idEstacion = ?
	             AND   isPoPqt = 1
	             AND   estado_po not in (7,8)";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array()['count'];
    }

    function getDataPqtCostoByCodCoti($cod_coti)
    {
        $sql = " SELECT pc.codigo_cluster,
					   idSubProyecto,
					   c.idEmpresaColab,
					   c.jefatura,
					   distancia_lineal
				  FROM planobra_cluster pc,
					   pqt_central c
				 WHERE codigo_cluster = ?
				   AND pc.idCentral = c.idCentral";
        $result = $this->db->query($sql, array($cod_coti));
        return $result->row_array();
    }

    //COSTO PAQUETIZADO PARA CUANDO COTIZA LA COLABORADORA
    function getCostoTotalPaquetizadoCotiEEcc($idSubProyecto, $idEmpresaColab, $idEstacion, $jefatura)
    {
        $sql = " SELECT SUM(tt.total_mo_pqt)total_mo_pqt
		           FROM (
					 SELECT pqe.id_tipo_partida,
							ptp.descripcion as tipoPreciario,
							pqe.descripcion as partidaPqt,
							pbs.baremo,
							1,
							pre.costo,
							ROUND(SUM(pbs.baremo * 1 * pre.costo),0) AS total_mo_pqt,
							FORMAT((pbs.baremo * 1 * pre.costo),2) as form,
							ROUND((pbs.baremo * 1 * pre.costo),2) as round,
							pqe.idActividad,
							CASE WHEN pre.tipoJefatura = 1 THEN 'LIMA'
								 WHEN pre.tipoJefatura = 2 THEN 'PROVINCIA' END tipoJefatura
					   FROM pqt_baremo_x_subpro_x_partida_mo pbs,
							pqt_partidas_paquetizadas_x_estacion pqe,
							pqt_tipo_preciario ptp,
							pqt_preciario pre
					  WHERE pbs.id_pqt_partida_mo_x_estacion    = pqe.id_tipo_partida
						AND pqe.id_pqt_tipo_preciario           = ptp.id
						AND ptp.id              = pre.idTipoPreciario
						AND pqe.idEstacion      = COALESCE(?, pqe.idEstacion) 
						AND pbs.idSubProyecto   = COALESCE(?, pbs.idSubProyecto)
						AND pre.idEmpresaColab  = COALESCE(?, pre.idEmpresaColab)  
						AND CASE WHEN ? = 'LIMA' THEN pre.tipoJefatura    = 1
								 ELSE pre.tipoJefatura = 2 END
					UNION ALL
						SELECT NULL,
							t.tipoPreciario,
							t.descPartida,
							t.baremo, 
							1, 
							t.costo,
							ROUND(SUM(t.baremo *  t.cantidad * t.costo),0) AS total_mo_pqt,
							FORMAT(SUM(t.baremo * t.cantidad * t.costo),2) as form,
							ROUND(SUM(t.baremo * t.cantidad * t.costo),2) as round,
							t.idActividad,
							t.tipoJefatura
						FROM (
							  SELECT ptp.descripcion as tipoPreciario,
									 pa.descripcion as descPartida,
									 pre.costo,
									 pa.baremo,
									 pa.idActividad,
									 CASE WHEN pre.tipoJefatura = 1 THEN 'LIMA'
										  WHEN pre.tipoJefatura = 2 THEN 'PROVINCIA' END tipoJefatura,
									 CASE WHEN pa.codigo = '69901-2' THEN ROUND(167.4/(baremo*costo),2) 
									      ELSE 1  END AS cantidad
								FROM
									partidas pa,
									pqt_tipo_preciario ptp,
									pqt_preciario pre
								WHERE pa.idPrecioDiseno = ptp.id
								  AND ptp.id = pre.idTipoPreciario
								  AND pa.codigo IN ('69901-2')
								  AND pre.idEmpresaColab  = COALESCE(?, pre.idEmpresaColab) 
								  AND CASE WHEN ? = 'LIMA' THEN pre.tipoJefatura = 1
										   ELSE pre.tipoJefatura = 2 END
						)t
					)tt";
        $result = $this->db->query($sql, array($idEstacion, $idSubProyecto, $idEmpresaColab, $jefatura, $idEmpresaColab, $jefatura));
        return $result->row_array();
    }

    function getMensajeHorarioRegItemSisego()
    {
        $sql = "SELECT CASE WHEN TIME(NOW()) >= '10:00:00' AND TIME(NOW()) <= '15:00:00' THEN 'SOLICITUD EN CONSULTA CON SAP, INTENTAR NUEVAMENTE A PARTIR DE LAS 15:00 PM' 
							WHEN TIME(NOW()) < TIME('10:00:00') THEN 'SOLICITUD EN CONSULTA CON SAP, VUELVA A INTENTARLO LUEGO DE LAS 10AM' 
							ELSE 'SOLICITUD EN CONSULTA CON SAP, INTENTAR MAÑANA DESPUES DE LAS 10 AM' END as msjTime";
        $result = $this->db->query($sql);
        return $result->row_array()['msjTime'];
    }

    function getLogSolicitudOc($itemplan)
    {
        $sql = "SELECT codigo_solicitud,
					   i.itemplan,
					   i.costo_unitario_mo,
					   CASE WHEN tipo_solicitud = 1 THEN 'CREACION OC'
							WHEN tipo_solicitud = 2 THEN 'EDICION OC'
							WHEN tipo_solicitud = 3 THEN 'CERTIFICACION OC'
							WHEN tipo_solicitud = 4 THEN 'ANULACION POS. OC' END tipo_solicitud,
						fecha_creacion,
						fecha_valida,
						(SELECT nombre
						   FROM usuario 
						  WHERE s.usuario_valida = id_usuario) usuario_valida,
					   CASE WHEN estado = 1 THEN 'PDT DE OC'
							WHEN estado = 2 THEN 'VALIDADO'
							WHEN estado = 3 THEN 'CANCELADO'
							WHEN estado = 4 THEN 'CERTIFICADO' END estado
				  FROM solicitud_orden_compra s,
					   itemplan_x_solicitud_oc i 
				 WHERE s.codigo_solicitud = i.codigo_solicitud_oc
                   AND i.itemplan = ?
				UNION ALL
			    SELECT codigo_solicitud,
					   i.itemplan,
					   i.costo_unitario_mo,
					   CASE WHEN tipo_solicitud = 1 THEN 'CREACION OC'
							WHEN tipo_solicitud = 2 THEN 'EDICION OC'
							WHEN tipo_solicitud = 3 THEN 'CERTIFICACION OC'
							WHEN tipo_solicitud = 4 THEN 'ANULACION POS. OC' END tipo_solicitud,
						fecha_creacion,
						fecha_valida,
						(SELECT nombre
						   FROM usuario 
						  WHERE s.usuario_valida = id_usuario) usuario_valida,
					   CASE WHEN estado = 1 THEN 'PDT DE OC'
							WHEN estado = 2 THEN 'VALIDADO'
							WHEN estado = 3 THEN 'CANCELADO'
							WHEN estado = 4 THEN 'CERTIFICADO' END estado
				  FROM itemplan_solicitud_orden_compra s,
					   itemplan_x_solicitud_oc i 
				 WHERE s.codigo_solicitud = i.codigo_solicitud_oc
                   AND i.itemplan = ?			 
				 ORDER BY fecha_creacion DESC";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->result_array();
    }

    function getIdPartidaByCod($codPartida)
    {
        $sql = "SELECT idActividad
				  FROM partidas
				 WHERE codigo = ? 
				GROUP BY idActividad";
        $result = $this->db->query($sql, array($codPartida));
        return $result->row_array()['idActividad'];
    }

    function insertMasivoPartidaProyecto($arrayData)
    {
        $this->db->insert_batch('proyecto_estacion_partida_mo', $arrayData);

        if ($this->db->affected_rows() < 0) {
            return array('error' => EXIT_ERROR, 'msj' => 'ERROR EN EL FORMATO DE LAS PARTIDAS, VERIFICAR.');
        } else {
            return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESARON LAS PARTIDAS CORRECTAMENTE.');
        }
    }

    function insertMasivoPartidaSubProPin($arrayData)
    {
        $this->db->insert_batch('actividad_x_subproyecto', $arrayData);

        if ($this->db->affected_rows() < 0) {
            return array('error' => EXIT_ERROR, 'msj' => 'ERROR EN EL FORMATO DE LAS PARTIDAS, VERIFICAR.');
        } else {
            return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESARON LAS PARTIDAS CORRECTAMENTE.');
        }
    }

    function getFlgSubSinDiseno($itemplan)
    {
        $sql = "SELECT s.flg_sin_diseno
				  FROM planobra po,
					   subproyecto s
				 WHERE po.idSubProyecto = s.idSubProyecto
				   AND po.itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['flg_sin_diseno'];
    }

    function getInfoItemplanWhitSubProyecto($itemplan)
    {
        $Query = "select po.itemplan, p.proyectoDesc as nombreProyecto, po.idFase,
						 po.idEstadoPlan, po.flg_opex, po.idSubProyecto, sp.idProyecto, po.pep2,
					     substring_index(po.pep2,'-',5) pep1,
						 po.ceco, po.cuenta, po.area_funcional,
						 po.idEmpresaColab,
                         sp.flg_reg_item_capex_opex
				from planobra po, subproyecto sp, proyecto p 
                where po.idSubProyecto = sp.idSubProyecto
                and sp.idProyecto = p.idProyecto
				and po.itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function hasOTACRecibida($itemplan, $itemplanac)
    {
        $query = "select count(1) as cant from planobra where itemplan = ? and utilmo_codigo_sirope_ac = ?";
        $result = $this->db->query($query, array($itemplan, $itemplanac));
        return $result->row()->cant;
    }

    function hasOTACCreada($itemplan, $itemplanac)
    {
        $query = "select count(1) as cant from log_tramas_sirope where itemplan = ? and codigo_ot = ? and estado = 1";
        $result = $this->db->query($query, array($itemplan, $itemplanac));
        return $result->row()->cant;
    }

    public function removeOTAC($itemplan, $codigo_ot, $log_planobra)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('codigo_ot', $codigo_ot);
            $this->db->where('estado', 1);
            $this->db->delete('log_tramas_sirope');
            if ($this->db->trans_status() === true) {
                $this->db->where('itemPlan', $itemplan);
                $this->db->update('planobra', array(
                    'utilmo_codigo_sirope_ac' => null,
                    'ultimo_estado_sirope_ac' => null
                ));
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar en planobra');
                } else {
                    $this->db->insert('log_planobra', $log_planobra);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar tabla log_planobra');
                    } else {
                        $this->db->trans_commit();
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se insert&oacute; correctamente!!';
                    }
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error transaccion DELETE config_autoaprob_po');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getDataCotizacionAll($estado, $flg_robot)
    {
        $sql = "SELECT codigo_cluster, latitud, longitud, distancia_lineal
				  FROM planobra_cluster 
				 WHERE estado = COALESCE(?, estado)  
				   AND flg_robot = COALESCE(?, flg_robot)
				   AND distancia_lineal IS NULL";
        $result = $this->db->query($sql, array($estado, $flg_robot));
        return $result->result_array();
    }

    function updateDistanciaCoti($codigo_cluster, $distancia)
    {
        $this->db->where('codigo_cluster', $codigo_cluster);
        $this->db->update('planobra_cluster', array('distancia_lineal' => $distancia));
    }

    function getDataActaCerti($itemplan, $codigo_solicitud, $orden_compra)
    {
        $sql = "SELECT t.itemplan,
					 t.nombreProyecto,
					 t.cesta,
					 t.orden_compra,
					 t.subProyectoDesc,
					 GROUP_CONCAT(DISTINCT t.group_posicion) group_posicion,
					 t.limite_costo_mo,
					 t.limite_costo_mat,
					 t.empresaColabDesc,
					 t.fecha_reg_sol,
					 t.codigo_solicitud,
					 t.costo_total,
					 t.responsable,
					 t.costo_sap,
					 t.proyectoDesc,
					 t.distrito,
					 t.departamento,
					 t.fechaEjecucion,
					 t.gerencia_desc
				FROM (
					 SELECT po.itemplan,
							po.nombreProyecto,
							s.cesta,
							s.orden_compra, 
							subProyectoDesc,
							GROUP_CONCAT(po.posicion) group_posicion, 
							FORMAT(i.costo_unitario_mo,2) as limite_costo_mo, 
							FORMAT(po.costo_unitario_mat,2) as limite_costo_mat,
							e.empresaColabDesc,
							(s.fecha_creacion) as fecha_reg_sol,
							s.codigo_solicitud,
							SUM(i.costo_unitario_mo) costo_total,
							(SELECT UPPER(u.nombre)
							   FROM gestor_responsable_x_proyecto g, 
									usuario u
							  WHERE g.idUsuario = u.id_usuario
								AND g.idProyecto = sp.idProyecto) as responsable,
							FORMAT(po.costo_sap, 2)costo_sap,
							p.proyectoDesc,
							c.distrito,
							c.departamento,
							CASE WHEN (po.fechaEjecucion IS NULL OR po.fechaEjecucion = '') THEN CASE WHEN (SELECT fecha_upd
																											 FROM control_estado_itemplan 
																											WHERE idEstadoPlan = 3
																											  AND itemplan = po.itemplan) IS NULL THEN po.fec_ult_ejec_diseno
																									  ELSE  (SELECT fecha_upd
																											   FROM control_estado_itemplan 
																											  WHERE idEstadoPlan = 3
																												AND itemplan = po.itemplan) END
								 ELSE po.fechaEjecucion END fechaEjecucion,
							  CASE WHEN p.idProyecto IN (1,26,27) THEN 'GERENCIA DE PLANIFICACION E INGENIERIA ACCESO FIJO'
							       ELSE 'GERENCIA DESPLIEGUE ACCESO FIJO Y PLANTA EXTERNA' END gerencia_desc
						FROM planobra po, 
								subproyecto sp,
								proyecto p,
								itemplan_x_solicitud_oc i, 
								solicitud_orden_compra s,
								empresacolab e,
								pqt_central c
						WHERE po.idSubProyecto = sp.idSubProyecto
							AND po.idCentralPqt = c.idCentral
							AND po.paquetizado_fg IN (1,2)
							AND e.idEmpresaColab = po.idEmpresaColab
							AND i.itemplan = po.itemplan
							AND s.codigo_solicitud = i.codigo_solicitud_oc
							AND po.itemplan 		  = COALESCE(?, po.itemplan)
							AND po.solicitud_oc_certi = COALESCE(?, po.solicitud_oc_certi)
							AND po.orden_compra       = COALESCE(?, po.orden_compra)
							AND p.idProyecto = sp.idProyecto
							AND s.tipo_solicitud = 3
						GROUP BY i.codigo_solicitud_oc
						UNION ALL
						SELECT po.itemplan,
								po.nombreProyecto,
								s.cesta,
								s.orden_compra, 
								subProyectoDesc,
								GROUP_CONCAT(po.posicion) group_posicion, 
								FORMAT(i.costo_unitario_mo,2) as limite_costo_mo, 
								FORMAT(po.costo_unitario_mat,2) as limite_costo_mat,
								e.empresaColabDesc,
								(s.fecha_creacion) as fecha_reg_sol,
								s.codigo_solicitud,
								SUM(i.costo_unitario_mo) costo_total,
								(SELECT UPPER(u.nombre)
								   FROM gestor_responsable_x_proyecto g, 
										usuario u
								  WHERE g.idUsuario = u.id_usuario
									AND g.idProyecto = sp.idProyecto) as responsable,
								FORMAT(po.costo_sap, 2)costo_sap,
								p.proyectoDesc,
								c.distrito,
								NULL,
								CASE WHEN (po.fechaEjecucion IS NULL OR po.fechaEjecucion = '') THEN CASE WHEN (SELECT fecha_upd
																												 FROM control_estado_itemplan 
																												WHERE idEstadoPlan = 3
																												  AND itemplan = po.itemplan) IS NULL THEN po.fec_ult_ejec_diseno
																										  ELSE  (SELECT fecha_upd
																												   FROM control_estado_itemplan 
																												  WHERE idEstadoPlan = 3
																													AND itemplan = po.itemplan) END
									 ELSE po.fechaEjecucion END fechaEjecucion,
								CASE WHEN p.idProyecto IN (1,26,27) THEN 'GERENCIA DE PLANIFICACION E INGENIERIA ACCESO FIJO'
							         ELSE 'GERENCIA DESPLIEGUE ACCESO FIJO Y PLANTA EXTERNA' END gerencia_desc
						FROM planobra po, 
								subproyecto sp,
								proyecto p,
								itemplan_x_solicitud_oc i, 
								solicitud_orden_compra s,
								empresacolab e,
								central c
						WHERE po.idSubProyecto = sp.idSubProyecto
							AND po.idCentral     = c.idCentral
							AND po.paquetizado_fg IS NULL
							AND e.idEmpresaColab = po.idEmpresaColab
							AND i.itemplan = po.itemplan
							AND s.codigo_solicitud = i.codigo_solicitud_oc
							AND po.itemplan           = COALESCE(?, po.itemplan)
							AND po.solicitud_oc_certi = COALESCE(?, po.solicitud_oc_certi)
							AND po.orden_compra       = COALESCE(?, po.orden_compra)
							AND p.idProyecto = sp.idProyecto
							AND s.tipo_solicitud = 3
						GROUP BY i.codigo_solicitud_oc
				)t";
        $result = $this->db->query($sql, array($itemplan, $codigo_solicitud, $orden_compra, $itemplan, $codigo_solicitud, $orden_compra));
        return $result->row_array();
    }

    function getRestriccionesAll($idTipoRestriccion, $estado)
    {
        $sql = "SELECT id_tipo_restriccion,  
					   descripcion as restriccionDesc
				  FROM tipo_restriccion t
			     WHERE t.id_tipo_restriccion = COALESCE(?, t.id_tipo_restriccion)
				   AND t.estado = ? ";
        $result = $this->db->query($sql, array($idTipoRestriccion, $estado));
        return $result->result();
    }

    function getLogRpaSapAprob($codigo_po)
    {
        $sql = "SELECT codigo_po, fecha,
					   CASE WHEN estado = 2 THEN UPPER(mensaje)
						    WHEN estado = 1 THEN 'EXITOSO' END mensaje
				  FROM log_tramas_rpa_sap 
				 WHERE codigo_po = ?
				  GROUP BY codigo_po,
				           estado";
        $result = $this->db->query($sql, array($codigo_po));
        return $result->result_array();
    }

    function updateCostosCoti($costoTotalMO, $costoTotalMAT, $arrayCostosCoti, $codigoCotizacion)
    {
        $this->db->trans_begin();

        $this->db->where('codigo_cluster', $codigoCotizacion);
        $this->db->update('planobra_cluster', $arrayCostosCoti);

        if ($this->db->affected_rows() > 0) {
            $sql = "UPDATE planobra po, planobra_cluster pc
			           SET costo_unitario_mo_crea_oc  = ?, 
					       costo_unitario_mo          = ?,
						   costo_unitario_mat_crea_oc = ?,
						   costo_unitario_mat         = ?
					  WHERE po.itemplan = pc.itemplan 
					    AND pc.codigo_cluster = ?
						AND po.solicitud_oc IS NULL";
            $this->db->query($sql, array($costoTotalMO, $costoTotalMO, $costoTotalMAT, $costoTotalMAT));

            if ($this->db->affected_rows() > 0) {
                $data['error'] = EXIT_SUCCESS;
                $this->db->trans_commit();
            } else {
                $this->db->trans_rollback();
                $data['msj'] = 'No se pudo actualizar los costos, verificar si no tiene una solicitud de orden de compra.';
                $data['error'] = EXIT_ERROR;
            }
        } else {
            $this->db->trans_rollback();
            $data['msj'] = 'No se pudo actualizar los costos, de la cotizacion, verificar que los datos se encuentren bien ingresados.';
            $data['error'] = EXIT_ERROR;
        }

        return $data;
    }

    function generarSolicitudCertiAnulEdiOC($itemplan, $nom_plan, $tipo_solicitud, $costo_edit_certi = null, $pep1 = null, $idUsuario)
    {
        $sql = "SELECT fn_create_solicitud_certi_oc(?, ?, ?, ?, ?, ?) AS flgValida";
        $result = $this->db->query($sql, array($itemplan, $nom_plan, $tipo_solicitud, $costo_edit_certi, $pep1, $idUsuario));
        return $result->row_array()['flgValida'];
    }

    function generarSolicitudAnulacionOpex($itemplan, $id_usuario)
    {
        $sql = "SELECT fn_create_solicitud_oc_anulacion_opex(?, ?) AS flgValida";
        $result = $this->db->query($sql, array($itemplan, $id_usuario));
        return $result->row_array()['flgValida'];
    }

    function insertRobotCv($arrayInsert)
    {
        $this->db->trans_begin();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->insert_batch('cv_robot_pedido', $arrayInsert);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }
    }

    function deleteRobotCv()
    {
        $sql = "DELETE FROM cv_robot_pedido";

        $this->db->query($sql);
    }

    function getCtoByCoordEdifCV($lat, $long)
    {
        $sql = "SELECT codigo,
					   ROUND(distancia,0) as distancia,
					   latitud, longitud, disponible_hilos,
					   ROUND(distancia+(distancia*0.30),0) as tendido,
					   id_terminal,
					   CASE WHEN tecnologia = 'GPON' OR (tecnologia = 'P2P' AND disponible_hilos = 0) THEN 1
					        ELSE 2 END flg_tipo_diseno,
					   tecnologia,
					   (SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
											 GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) lg
						   FROM vias_metropolitanas_ubicacion
						   ORDER BY lg DESC
						   limit 1) flg_cruce
				  FROM 
					(
					SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_terminal,tecnologia,tipo_pa, id
						FROM (
								SELECT pow(cos(latTo) * sin(lonDelta), 2) +
										pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
										sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
										codigo,
										latitud, longitud,
										disponible_hilos,
										id_terminal,
										tecnologia,
										tipo_pa,
										id
								FROM (
										SELECT radians(latitud)  AS latFrom,
											   radians(" . $long . ") - radians(longitud) AS lonDelta,
											   radians(" . $long . ") lonTo,
											   id_terminal,
											   radians(" . $lat . ") as  latTo,
												codigo,
												latitud, longitud,
												total_hilos-ocupacion_hilos as disponible_hilos,
												tecnologia,
												tipo_pa,
												id
										  FROM cto_ubicacion_edificio
										)t
							)tt
						HAVING (disponible_hilos > 0 AND tipo_pa IN ('0 - Site Holder','10 - Fachada','11 - Punto de acceso de edificio','4 - Punto de acceso genérico')
								AND tecnologia = 'P2P')
						ORDER BY distancia ASC
						limit 3
						)ttt
					 GROUP BY id";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getCtoDistanciaUnCTOCV($lat, $long)
    {
        $sql = "SELECT GROUP_CONCAT(DISTINCT codigo),
					   ROUND(distancia,0) as distancia,
					   ROUND(distancia+(distancia*0.30),0) as tendido,
					   latitud, longitud, disponible_hilos,
					   id_terminal,
					   CASE WHEN tecnologia = 'GPON' OR (tecnologia = 'P2P' AND disponible_hilos = 0) THEN 1
					        ELSE 2 END flg_tipo_diseno,
					   tecnologia
				  FROM 
					(
					SELECT (atan2(sqrt(a), b))*6371000 as distancia, codigo, latitud, longitud, disponible_hilos, id_terminal,tecnologia,tipo_pa
						FROM (
								SELECT pow(cos(latTo) * sin(lonDelta), 2) +
										pow(cos(latFrom) * sin(latTo) - sin(latFrom) * cos(latTo) * cos(lonDelta), 2) a,
										sin(latFrom) * sin(latTo) + cos(latFrom) * cos(latTo) * cos(lonDelta) b,
										codigo,
										latitud, longitud,
										disponible_hilos,
										id_terminal,
										tecnologia,
										tipo_pa
								FROM (
										SELECT radians(latitud)  AS latFrom,
											   radians(" . $long . ") - radians(longitud) AS lonDelta,
											   radians(" . $long . ") lonTo,
											   id_terminal,
											   radians(" . $lat . ") as  latTo,
												codigo,
												latitud, longitud,
												total_hilos-ocupacion_hilos as disponible_hilos,
												tecnologia,
												tipo_pa
										  FROM cto_ubicacion_edificio
										)t
							)tt
						HAVING (disponible_hilos > 0 AND tipo_pa IN ('0 - Site Holder','10 - Fachada','11 - Punto de acceso de edificio','4 - Punto de acceso genérico')
								AND tecnologia = 'P2P')
						ORDER BY distancia ASC
						limit 1
						)ttt";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    function getCounExpedienteItemplanByItem($itemplan, $estado)
    {
        $sql = "SELECT COUNT(1) count
				  FROM itemplan_expediente 
				 WHERE itemplan = ?
				   AND estado   = ?";
        $result = $this->db->query($sql, array($itemplan, $estado));
        return $result->row_array()['count'];
    }

    function getDataRobotCv()
    {
        $sql = "SELECT itemplan,
					   latitud,
					   longitud,
					   facilidad,
					   distancia_lineal,
					   tendido,
					   tipo,
					   CASE WHEN flg_vias_metro = 1 THEN 'LA CTO CRUZA UNA VIA METROPOLITANA'
					        ELSE 'NO CRUZA UNA VIA METROPOLITANA' END as estatus_via
				  FROM cv_robot_pedido";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getSubProyectoOpex($descripcion)
    {
        $sql = " SELECT idSubProyecto  
				   FROM subproyecto
				  WHERE id_sub_capex_opex = ( SELECT idSubProyecto 
												  FROM subproyecto 
												 WHERE subProyectoDesc = ?);";
        $result = $this->db->query($sql, array($descripcion));

        if ($result->row() != null) {
            return $result->row_array()['idSubProyecto'];
        } else {
            return null;
        }
    }

    function getFlgViasMetrosPolitanas($long, $lat)
    {
        $sql = " SELECT ST_Intersects(GeomFromText(coordenadas_vias), 
									  GeomFromText(GROUP_CONCAT('MULTILINESTRING ((', CAST(longitud AS CHAR),' ',CAST(latitud AS CHAR),', " . $long . " " . $lat . "))'))) flg_vias
				   FROM vias_metropolitanas_ubicacion
				   ORDER BY lg DESC
				   limit 1";
        $result = $this->db->query($sql);
        return $result->row_array()['flg_vias'];
    }

    function requiereOTCoaxial($idSubProyecto)
    {
        $query = "SELECT   COUNT(1) as count
	              FROM     subproyecto_requiere_sirope_coaxial 
	              WHERE    idSubProyecto = ?";
        $result = $this->db->query($query, array($idSubProyecto));
        return $result->row()->count;
    }

    function NorequiereOTFO($idSubProyecto)
    {
        $query = "SELECT   COUNT(1) as count
	              FROM     subproyecto_no_requieren_sirope
	              WHERE    idSubProyecto = ?";
        $result = $this->db->query($query, array($idSubProyecto));
        return $result->row()->count;
    }

    function getAllProyectoTransporte()
    {
        $Query = "SELECT * 
                    FROM proyecto
                    WHERE flgModTransporte = 1";
        $result = $this->db->query($Query, array());
        return $result;
    }

    function getAllSubProyectoTrasporte()
    {
        $sql = "SELECT * 
                  FROM proyecto p,
                       subproyecto s
                 WHERE flgModTransporte = 1
                   AND p.idProyecto = s.idProyecto";
        $result = $this->db->query($sql);
        return $result;
    }

    function getCountItemExpediente($itemplan, $estado)
    {
        $sql = "SELECT COUNT(1) countExp 
				  FROM itemplan_expediente i
				 WHERE itemplan = ?
				   AND estado = ?";
        $result = $this->db->query($sql, array($itemplan, $estado));
        return $result->row_array()['countExp'];
    }

    function getAllPostes()
    {
        $sql = "  SELECT codigo,
						 tipo,
						 propietario,
						 substring_index(substring_index(substring_index(coord_punto_poste, '(',-1),')',1),' ',1) AS longitud,
						 substring_index(substring_index(substring_index(coord_punto_poste, '(',-1),')',1),' ',-1) AS latitud
					FROM poste_ubicacion";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getDataObraTransporteRow($itemplan)
    {
        $sql = "SELECT * 
		          FROM planobra_transporte
				 WHERE itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    function getCodigoPOTransporte($itemplan)
    {
        $Query = "SELECT getPoCod_transporte(?) as codigoPO";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['codigoPO'];
        } else {
            return null;
        }
    }

    function updatePlanObraTransporte($itemplan, $dataUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra_transporte', $dataUpdate);

            if ($this->db->affected_rows() != 1) {
                throw new Exception('Error al modificar el updateEstadoPlanObra');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function getCountSisegoUmCoti($sisego)
    {
        $sql = "SELECT count(1) AS count
                  FROM cotizacion_um_planta_externa
				WHERE sisego = ?";
        $result = $this->db->query($sql, array($sisego));
        return $result->row_array()['count'];
    }

    function insertLogTransportePo($dataLogPO)
    {
        $this->db->insert('log_planobra_po_transporte', $dataLogPO);
        if ($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'NO SE REGISTRO EL LOG DE PO.';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se actualizo correctamente!';
        }
        return $data;
    }

    function getYearActual()
    {
        $sql = "SELECT EXTRACT(YEAR FROM NOW()) as year";
        $result = $this->db->query($sql);
        return $result->row_array()['year'];
    }

    function getContratoAll($estado)
    {
        $sql = "SELECT id_contrato,
					   nombre,
					   alias
				  FROM contrato
				 WHERE estado = ?";
        $result = $this->db->query($sql, array($estado));
        return $result->result_array();
    }

    function getPtrPlantaInterna($itemplan)
    {
        $sql = " SELECT ptr, 
		                estado, 
						usua_crea, 
						fecha_crea, 
						ultimo_estado, 
						2 AS flg_tipo
				   FROM ptr_planta_interna 
				  WHERE rangoPtr <> 6
					AND itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    function getPorcentajeLiqui()
    {
        $sql = "SELECT id,
                       porcentaje 
                  FROM porcentaje_liqui";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function insertEditPo($arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            foreach ($arrayData as $row) {
                $this->db->where('codigo_po', $row['codigo_po']);
                $this->db->where('idActividad', $row['idActividad']);
                $this->db->delete('planobra_po_detalle_edit');

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('No se ingreso correctamente');
                }
            }
            $this->db->insert_batch('planobra_po_detalle_edit', $arrayData);

            if ($this->db->affected_rows() == 0) {
                throw new Exception('No se ingreso correctamente');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj']   = 'TODO SALIO CORRECTO';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }


    function insertPorcentajeLiqui($arrayDataInsert)
    {
        $this->db->insert('itemplanestacionavance', $arrayDataInsert);

        if ($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'NO SE INGRESO EL PORCENTAJE, VERIFICAR';
        } else {
            $data['error'] = EXIT_SUCCESS;
        }
        return $data;
    }

    function consultaUpdatePOLiquidacion($itemplan, $ptr)
    {
        $sql = " SELECT DISTINCT 
                        acpi.descripcion,
                        ppi.ptr,
                        pxa.precio,
                        pxa.baremo,
                        pxa.cantidad_inicial,
                        pxa.costo_mo,
                        acpi.costo_material,
                        pxa.total,
                        pxa.idActividad,
                        pxa.cantidad_final,
                        pxa.id_ptr_x_actividades_x_zonal
                   FROM ptr_planta_interna ppi,
                        planobra_po_detalle_edit pxa,
                        partidas acpi
                  WHERE ppi.ptr          = pxa.codigo_po
                    AND acpi.idActividad = pxa.idActividad
                    AND acpi.flg_tipo    = 1
                    AND ppi.ptr          = '" . $ptr . "'
                    AND ppi.itemplan     = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getDataPoTransp($itemplan, $ptr)
    {
        $sql = " SELECT DISTINCT 
                        acpi.descripcion,
                        ppi.ptr,
                        pxa.precio,
                        pxa.baremo,
                        pxa.cantidad,
                        pxa.costo_mo,
                        acpi.costo_material,
						acpi.codigo,
                        pxa.total,
                        pxa.id_actividad,
                        pxa.cantidad_final,
                        pxa.id_ptr_x_actividades_x_zonal,
						ppe.cantidad_final as cantidad_editado,
						ppe.total as total_editado,
						po.idContrato
                   FROM (ptr_planta_interna ppi,
                        ptr_x_actividades_x_zonal pxa,
                        partidas acpi,
						planobra po)
			  LEFT JOIN planobra_po_detalle_edit ppe
			         ON ppe.codigo_po = pxa.ptr AND ppe.estado IS NULL AND ppe.idActividad = pxa.id_actividad
                  WHERE ppi.ptr          = pxa.ptr
                    AND acpi.idActividad = pxa.id_actividad
                    AND acpi.flg_tipo    = 1
                    AND ppi.ptr          = '" . $ptr . "'
                    AND ppi.itemplan     = '" . $itemplan . "'
					AND ppi.itemplan     = po.itemplan
			UNION ALL
				    SELECT pe.descripcion,
                           codigo_po,
                           precio,
                           pe.baremo,
                           cantidad_inicial,
                           costo_mo,
                           0 as costo_material,
						   pa.codigo,
                           0 as total,
                           pe.idActividad,
                           cantidad_final,
                           id_ptr_x_actividades_x_zonal,
                           cantidad_final as cantidad_editado,
                           total as total_editado,
						   po.idContrato
                      FROM planobra_po_detalle_edit pe, planobra po, partidas pa
					 WHERE codigo_po    = '" . $ptr . "'
                       AND pe.itemplan     = '" . $itemplan . "'
					   AND pa.idActividad  = pe.idActividad
					   AND pe.itemplan     = po.itemplan
                       AND NOT EXISTS (SELECT 1 
									  FROM ptr_x_actividades_x_zonal
									 WHERE ptr = codigo_po
									   AND id_actividad = pe.idActividad)";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function insertEditPoEnDetallePo($itemplan, $arrayDataUpdatePO)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $sql = "DELETE pd.* 
					  FROM planobra_po_detalle_edit pde,
						   ptr_x_actividades_x_zonal pd
					 WHERE pde.id_ptr_x_actividades_x_zonal = pd.id_ptr_x_actividades_x_zonal
					   AND pde.itemplan = ?
					   AND pde.estado IS NULL";
            $this->db->query($sql, array($itemplan));

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('No se ingreso correctamente');
            } else {
                $sql = "INSERT INTO ptr_x_actividades_x_zonal  (ptr,itemplan,id_actividad,descripcion, cantidad, cantidad_final,costo_mo, baremo, precio,total,id_ptr_x_actividades_x_zonal)
						SELECT codigo_po, itemplan, idActividad, descripcion, cantidad_inicial, cantidad_final, costo_mo, baremo, precio, total, id_ptr_x_actividades_x_zonal
						  FROM planobra_po_detalle_edit
						 WHERE itemplan = ?
						   AND estado IS NULL";
                $this->db->query($sql, array($itemplan));
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('No se ingreso correctamente');
                } else {
                    $this->db->where('itemplan', $itemplan);
                    $this->db->update('planobra_po_detalle_edit', $arrayDataUpdatePO);

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('No se ingreso correctamente');
                    } else {
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj']   = 'TODO SALIO CORRECTO';
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function insertSolicitudOcEdi($arrayData, $fecha, $cod_solicitud, $costoFinal, $itemplan)
    {
        try {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = null;

            $sql = "INSERT INTO solicitud_orden_compra (codigo_solicitud, orden_compra, idEmpresaColab, estado, fecha_creacion, idSubProyecto, plan, pep1, pep2, tipo_solicitud, estatus_solicitud, cesta)
					VALUES (?, ?, ?, 1, ?, ?, 'PLAN', ?, ?, 2, 'NUEVO',?);";
            $this->db->query($sql, array(
                $cod_solicitud, $arrayData['orden_compra'], $arrayData['idEmpresaColab'], $fecha, $arrayData['idSubProyecto'],
                $arrayData['pep1'], $arrayData['pep2'], $arrayData['cesta']
            ));

            if ($this->db->affected_rows() > 0) {
                $sql = "INSERT INTO itemplan_x_solicitud_oc(itemplan, codigo_solicitud_oc, costo_unitario_mo)
						VALUES (?, ?, ?)";
                $this->db->query($sql, array($arrayData['itemplan'], $cod_solicitud, $costoFinal));

                if ($this->db->affected_rows() < 1) {
                    throw new Exception("No ingreso la solicitud OC.");
                } else {
                    $this->db->where('itemplan', $itemplan);
                    $this->db->update('planobra', array(
                        'solicitud_oc_dev' => $cod_solicitud,
                        'costo_devolucion' => $costoFinal,
                        'estado_oc_dev'    => 'PENDIENTE'
                    ));
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj']   = 'Se ingreso correctamente!';
                }
            }
        } catch (Exception $e) {
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }

        return $data;
    }


    function insertSolicitudOcCerti($arrayData, $fecha, $cod_solicitud, $itemplan, $costo_mo, $estadoCerti)
    {
        try {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = null;

            $sql = "INSERT INTO solicitud_orden_compra (codigo_solicitud, orden_compra, idEmpresaColab, estado, fecha_creacion, idSubProyecto, plan, pep1, pep2, tipo_solicitud, estatus_solicitud, cesta)
					VALUES (?, ?, ?, ?, ?, ?, 'PLAN', ?, ?, 3, 'NUEVO',?);";
            $this->db->query($sql, array(
                $cod_solicitud, $arrayData['orden_compra'], $arrayData['idEmpresaColab'], $estadoCerti, $fecha, $arrayData['idSubProyecto'],
                $arrayData['pep1'], $arrayData['pep2'], $arrayData['cesta']
            ));
            if ($this->db->affected_rows() > 0) {
                $sql = "INSERT INTO itemplan_x_solicitud_oc(itemplan, codigo_solicitud_oc, costo_unitario_mo)
						VALUES (?, ?, ?)";
                $this->db->query($sql, array($arrayData['itemplan'], $cod_solicitud, $costo_mo));

                if ($this->db->affected_rows() < 1) {
                    throw new Exception("No ingreso la solicitud OC.");
                } else {
                    $this->db->where('itemplan', $itemplan);
                    $this->db->update('planobra', array(
                        'solicitud_oc_certi'      => $cod_solicitud,
                        'costo_unitario_mo_certi' => $costo_mo
                    ));
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj']   = 'Se ingreso correctamente!';
                }
            }
        } catch (Exception $e) {
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }

        return $data;
    }

    function getDataSolicitudOc($itemplan)
    {
        $sql = " SELECT s.estado,
						s.pep1,
						s.pep2,
						s.cesta,
						po.idEmpresaColab,
						po.idSubProyecto,
						po.itemplan,
						s.orden_compra,
						po.costo_unitario_mo
				   FROM solicitud_orden_compra s,
						planobra po
				  WHERE s.codigo_solicitud = po.solicitud_oc
					AND po.itemplan = ?
					AND s.orden_compra IS NOT NULL";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    function getDetallePoTransp($codigo_po, $itemplan = null)
    {
        $sql = "  SELECT ppd.ptr as codigo_po,
						 cantidad as cantidad_inicial,
						 ppd.cantidad_final,
						 pa.codigo,
						 UPPER(ppd.descripcion) as descPartida,
						 ppd.baremo,
						 ppd.total,
						 ppd.costo_mo,
						 ppd.precio
					FROM ptr_x_actividades_x_zonal ppd, 
					     partidas pa,
						 ptr_planta_interna pp,

                         partida_x_contrato pxc,
                         planobra po,
                         contrato co
				   WHERE ppd.ptr = COALESCE(?, ppd.ptr)
				     AND ppd.itemplan = COALESCE(?, ppd.itemplan)
					 AND pa.idActividad = ppd.id_actividad
					 AND ppd.ptr = pp.ptr 
					 AND pp.rangoPtr <> 6
                     
                     AND po.itemplan = ppd.itemplan
                     AND po.idContrato      = pxc.id_contrato
					 AND po.idEmpresaColab  = pxc.idEmpresaColab 
				     AND pa.idActividad     = pxc.id_partida
                     AND pxc.id_contrato    = co.id_contrato
                     UNION ALL
                     
                    SELECT ppd.ptr as codigo_po,
						 cantidad as cantidad_inicial,
						 ppd.cantidad_final,
						 pxc.codigo,
						 UPPER(ppd.descripcion) as descPartida,
						 ppd.baremo,
						 ppd.total,
						 ppd.costo_mo,
						 ppd.precio
					FROM ptr_x_actividades_x_zonal ppd, 
					     partidas pa,
						 ptr_planta_interna pp,

                         partida_x_zona_x_empresacolab_x_subproyecto pxc,
                         planobra po,
                         contrato co
				   WHERE ppd.ptr = COALESCE(?, ppd.ptr)
				     AND ppd.itemplan = COALESCE(?, ppd.itemplan)
					 AND pa.idActividad = ppd.id_actividad
					 AND ppd.ptr = pp.ptr 
					 AND pp.rangoPtr <> 6
                     
                     AND po.itemplan = ppd.itemplan
                     AND po.idContrato      = co.id_contrato
					 AND po.idEmpresaColab  = pxc.idEmpresaColab 
				     AND pa.idActividad     = pxc.id_partida
					 AND pxc.idSubProyecto  = po.idSubProyecto
				     AND pxc.idZona    = po.idZona";
        $result = $this->db->query($sql, array($codigo_po, $itemplan, $codigo_po, $itemplan));
        return $result->result_array();
    }


    function insertLogEditPo($arrayDataInsert)
    {
        $this->db->insert('log_edit_po', $arrayDataInsert);

        if ($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'NO SE INGRESO LOG DE EDICION, VERIFICAR';
        } else {
            $data['error'] = EXIT_SUCCESS;
        }
        return $data;
    }

    function getDataConfigOpex($estado)
    {
        $sql = "SELECT GROUP_CONCAT(ceco,' - ',cuenta,' - ',area_funcional) as descripcion_cuenta
				  FROM opex_configuracion
				 WHERE estado = ?
				GROUP BY ceco,cuenta,area_funcional";
        $result = $this->db->query($sql, array($estado));
        return $result->result_array();
    }

    function getPartidasByProyectoEstacion($itemplan, $idEstacion, $idContrato)
    {
        $Query = 'SELECT pa.idActividad, pa.codigo, pa.descripcion, co.nombre as descPrecio, pa.baremo, pxc.costo  
				    FROM planobra po,
					     partida_x_contrato pxc,
					     partidas pa,
					     contrato co
				   WHERE po.itemplan = ?
				     AND po.idContrato      = pxc.id_contrato
					 AND po.idEmpresaColab  = pxc.idEmpresaColab 
				     AND pa.idActividad     = pxc.id_partida
				     AND pxc.id_contrato    = co.id_contrato
					 AND po.idContrato      = pxc.id_contrato
					 AND pxc.id_contrato    = po.idContrato
					 AND co.id_contrato_padre <> 1
				UNION ALL
				(
				 SELECT pa.idActividad, pxc.codigo, pa.descripcion, co.nombre as descPrecio, pa.baremo, pxc.costo  
				    FROM planobra po,
						 partida_x_zona_x_empresacolab_x_subproyecto pxc,
					     partidas pa,
					     contrato co
				   WHERE po.itemplan = ?
				     AND po.idContrato      = co.id_contrato
					 AND po.idEmpresaColab  = pxc.idEmpresaColab 
				     AND pa.idActividad     = pxc.id_partida
					 AND pxc.idSubProyecto  = po.idSubProyecto
				     AND pxc.idZona    = po.idZona )';
        $result = $this->db->query($Query, array($itemplan, $itemplan));
        return $result->result();
    }

    function getDataItemplanByCodOcCerti($codigoSolicitudCerti)
    {
        $sql = "SELECT po.itemplan, 
		               po.idEstadoPlan
                  FROM planobra po,
				       itemplan_x_solicitud_oc i
                 WHERE i.codigo_solicitud_oc = ?
				   AND po.itemplan = i.itemplan
				GROUP BY po.itemplan";
        $result = $this->db->query($sql, array($codigoSolicitudCerti));
        return $result->row_array();
    }

    //carga de contrato por subproyecto
    function getContratoPadre($idSubProyecto)
    {
        $sql = "  SELECT cp.id_contrato_padre,
                         cp.nombre,
                         cp.fecha_registro,
                         cp.estado
                    FROM contrato_padre cp,
                         contrato_padre_x_subproyecto cpxs
                   WHERE cp.id_contrato_padre = cpxs.id_contrato_padre
                     AND cp.estado = 1
                     AND cpxs.idSubProyecto = ?
                ORDER BY 1 ";
        $result = $this->db->query($sql, array($idSubProyecto));
        return $result->result_array();
    }

    function getContratoPadre_Liberado($idSubProyecto)
    {
        $sql = "  SELECT  DISTINCT cp.id_contrato_padre,
                         cp.nombre,
                         cp.fecha_registro,
                         cp.estado
                    FROM contrato_padre cp
                   WHERE cp.estado = 1
                    
                ORDER BY 1 ";
        $result = $this->db->query($sql, array($idSubProyecto));
        return $result->result_array();
    }

    function getContratoByIdContratoPadreEECC($idContratoPadre, $idEmpresaColab)
    {
        $sql = "   SELECT *
				     FROM contrato
				    WHERE estado = 1
					  AND id_contrato_padre = ? 
                      AND idEmpresaColab = ? 
                 ORDER BY nombre ASC ";
        $result = $this->db->query($sql, array($idContratoPadre, $idEmpresaColab));
        return $result->result_array();
    }

    function getAllEmpresaColabByIdContratoPadre($idContratoPadre = null)
    {
		$idUsuario   = $this->session->userdata('idPersonaSession');
        $sql = "   SELECT *
                     FROM empresacolab e, contrato_padre_x_empresacolab ce
                    WHERE ce.id_contrato_padre = COALESCE(?, ce.id_contrato_padre)
                      AND e.idEmpresaColab = ce.idEmpresaColab
					  AND ce.idEmpresaColab <> 13
					  -- AND e.idEmpresaColab NOT IN (14)# A SOLICITUD DE OWEN 17-02-2023
                 UNION ALL
                    
                    SELECT *
                     FROM empresacolab e, contrato_padre_x_empresacolab ce
                    WHERE ce.id_contrato_padre = COALESCE(1, ce.id_contrato_padre)
                      AND e.idEmpresaColab = ce.idEmpresaColab
                      AND CASE WHEN ".$idUsuario." IN (77,2742,3) THEN e.idEmpresaColab = 13 ELSE false END
					  -- AND e.idEmpresaColab NOT IN (14)# A SOLICITUD DE OWEN 17-02-2023
                 ORDER BY empresaColabDesc, id_contrato_padre";
        $result = $this->db->query($sql, array($idContratoPadre));
		_log($this->db->last_query());
        return $result->result_array();
    }


    function getEstacionDBSAM()
    {
        $sql = " SELECT * FROM inventario_db.company LIMIT 1 ";
        $result = $this->db->query($sql, array());
        return $result->result_array();
    }

    function generarSolicitudCertiEdicionOPEX($itemplan, $idUsuario, $costo_total, $tipo_plan, $id_opex = null)
    {
        $sql = "SELECT fn_create_solicitud_oc_edit_certi_opex(?, ?, ?, ?, ?) AS flgValida";
        $result = $this->db->query($sql, array($itemplan, $idUsuario, $costo_total, $id_opex, $tipo_plan));
        return $result->row_array()['flgValida'];
    }

    function getInforUsuarioByIdUsuario($idUsuario)
    {
        $Query = "SELECT    * FROM usuario where id_usuario = ?";

        $result = $this->db->query($Query, array($idUsuario));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
	
	function getUsuarioByUsuario($usuario)
    {
        $Query = "SELECT * 
		            FROM usuario 
				   WHERE usuario = ?";
        $result = $this->db->query($Query, array($usuario));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    public function hasPtrOnByidSubProyecto($idSubProyecto)
    {
        $sql = "select count(1) as cant 
				from ptr_planta_interna pt, planobra po
				where pt.itemplan = po.itemplan
				and po.idSubProyecto =	?";
        $result = $this->db->query($sql, array($idSubProyecto));
        return $result->row_array()['cant'];
    }

    function getPtrByItemplanForBanValidacion($itemplan)
    {
        $sql = "SELECT ptr, FORMAT(SUM(total_anterior), 2) as total_anterior, ROUND(SUM(total), 2) total 
				  FROM (
						SELECT ppo.ptr,
							   ppo.total as total_anterior,
							   CASE WHEN pe.idActividad IS NOT NULL THEN pe.total ELSE ppo.total END total
						  FROM ptr_x_actividades_x_zonal ppo
						  LEFT JOIN planobra_po_detalle_edit pe
						    ON ppo.ptr = pe.codigo_po
						   AND pe.idActividad = ppo.id_actividad,
                               ptr_planta_interna pp
						 WHERE ppo.ptr = pp.ptr
                           AND pp.rangoPtr != 6
                           AND ppo.itemplan = '" . $itemplan . "' 
					UNION ALL 
						SELECT pe.codigo_po,
							   0 as total_anterior,
							   CASE WHEN pe.idActividad IS NOT NULL THEN pe.total ELSE ppo.total END total
						  FROM planobra_po_detalle_edit pe 
						  LEFT JOIN ptr_x_actividades_x_zonal ppo
						    ON ppo.ptr = pe.codigo_po
						   AND pe.idActividad = ppo.id_actividad,
                               ptr_planta_interna pp
						 WHERE pe.codigo_po = pp.ptr
                           AND pp.rangoPtr != 6
                           AND pe.itemplan = '" . $itemplan . "'
						   AND ppo.ptr IS NULL
						)t GROUP BY ptr";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getEntidadByItemplanEstacion($itemplan, $idEstacion, $idEntidad = null, $id = null)
    {
        $sql = "SELECT ei.*,
                       e.desc_entidad,
                       t.flg_comprobante,
                       po.idEstadoPlan,
					   po.flgLicencia
                  FROM (entidad_itemplan_estacion ei,
                       entidad e,
					   planobra po)
             LEFT JOIN tipo_entidad t ON (t.id_tipo_entidad = ei.idTipoEntidad)
                 WHERE ei.itemplan   = ?
                   AND ei.idEstacion = COALESCE(?, ei.idEstacion)
                   AND ei.idEntidad = COALESCE(?, ei.idEntidad)
				   AND ei.id = COALESCE(?, ei.id)
                   AND ei.idEntidad = e.idEntidad
				   AND ei.itemplan = po.itemplan ";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $idEntidad, $id));
        return $result->result_array();
    }

    function getEntidades($itemplan, $idEstacion)
    {
        $sql = "  SELECT e.*,
                         (  SELECT 1
                              FROM entidad_itemplan_estacion
                             WHERE idEntidad = e.idEntidad
                               AND itemplan = ?
                               AND idEstacion = ?
                             LIMIT 1) AS marcado
                    FROM entidad e
                   WHERE e.estado = 1 ";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->result();
    }

    function getTipoEntidadAll($idEntidad = null)
    {
        $sql = " SELECT t.id_tipo_entidad, 
                        t.nombre as tipoEntidadDesc, 
                        t.estado,
                        t.idEntidad,
                        t.flg_compromiso
                   FROM tipo_entidad t
                  WHERE t.estado = 1
                     AND CASE WHEN 11 = ? THEN t.idEntidad IN (11) ELSE t.idEntidad IS NULL END;";
        $result = $this->db->query($sql, $idEntidad);
        return $result->result_array();
    }

    function getDistritoAll()
    {
        $sql = " SELECT d.idDistrito, 
                        d.distritoDesc, 
                        d.estado
                   FROM distrito d
                  WHERE d.estado = 1 ";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getEntidadAll($itemplan)
    {
        $sql = "SELECT e.idEntidad,
                       e.desc_entidad 
                  FROM entidad e
                 WHERE  e.estado = 1 ";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function registrarEntidad($arrayData)
    {
        $this->db->insert_batch('entidad_itemplan_estacion', $arrayData);
        if ($this->db->affected_rows() > 0) {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insertó correctamente!';
        } else {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'Error al insertar en la tabla entidad.';
        }

        return $data;
    }

    function actualizarLicencia($jsonExp)
    {
        $this->db->where('id', $jsonExp['id']);
        $this->db->update('entidad_itemplan_estacion', $jsonExp);
        if ($this->db->affected_rows() <= 0) {
            $data['msj'] = 'No se actualizo la entidad.';
            $data['error'] = EXIT_ERROR;
        } else {
            $data['msj'] = 'Se registro correctamente';
            $data['error'] = EXIT_SUCCESS;
        }

        return $data;
    }

    function getCountEntidadPendiente($itemplan)
    {
        $sql = "SELECT COUNT(1) count 
                  FROM entidad_itemplan_estacion 
				 WHERE (estado = 1 OR estado IS NULL)
				   AND itemplan = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
    }

    function registrarLogLicencia($dataLog)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->insert('log_entidad_itemplan_estacion', $dataLog);
            if ($this->db->affected_rows() <= 0) {
                $data['msj'] = 'No se registró el log en la tabla log_entidad_itemplan_estacion';
                $data['error'] = EXIT_ERROR;
            } else {
                $data['msj'] = 'Se registró correctamente';
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function eliminarEntidadItemplanEstacionById($id)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->where('id', $id);
            $this->db->delete('entidad_itemplan_estacion');

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al eliminar en la tabla entidad_itemplan_estacion!!');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se eliminó correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getCountTipoSolicitud($itemplan, $tipoSolicitud, $estado)
    {
        $sql = "SELECT COUNT(1) count
				  FROM planobra po,
					   itemplan_x_solicitud_oc ixs,
					   itemplan_solicitud_orden_compra s
				 WHERE ixs.itemplan = po.itemplan
				   AND po.itemplan = ?
				   AND s.codigo_solicitud = ixs.codigo_solicitud_oc
				   AND s.tipo_solicitud = ?
				   AND s.estado         = COALESCE(?, s.estado)";
        $result = $this->db->query($sql, array($itemplan, $tipoSolicitud, $estado));
        return $result->row_array()['count'];
    }

    function updateSolicitudOcByItemplan($itemplan, $tipoSolicitud, $estado, $estadoFin)
    {
        $sql = "UPDATE planobra po,
					   itemplan_x_solicitud_oc ixs,
					   itemplan_solicitud_orden_compra s
				   SET s.estado = ?
				 WHERE ixs.itemplan = po.itemplan
				   AND po.itemplan      = ?
				   AND s.codigo_solicitud = ixs.codigo_solicitud_oc
				   AND s.tipo_solicitud = ?
				   AND s.estado         = ?";
        $this->db->query($sql, array($estadoFin, $itemplan, $tipoSolicitud, $estado));
        if ($this->db->affected_rows() <= 0) {
            $data['msj'] = 'No se registro';
            $data['error'] = EXIT_ERROR;
        } else {
            $data['msj'] = 'Se registro correctamente';
            $data['error'] = EXIT_SUCCESS;
        }
        return $data;
    }

    function getCountTipoSolicitudCapex($itemplan, $tipoSolicitud, $estado)
    {
        $sql = "SELECT COUNT(1) count
				  FROM planobra po,
					   itemplan_x_solicitud_oc ixs,
					   solicitud_orden_compra s
				 WHERE ixs.itemplan = po.itemplan
				   AND po.itemplan = ?
				   AND s.codigo_solicitud = ixs.codigo_solicitud_oc
				   AND s.tipo_solicitud = ?
				   AND s.estado         = COALESCE(?, s.estado)";
        $result = $this->db->query($sql, array($itemplan, $tipoSolicitud, $estado));
        return $result->row_array()['count'];
    }

    function getCountPresupuestoSap($pep1, $total)
    {
        $sql = "SELECT COUNT(1) count
				  FROM sap_detalle
				 WHERE pep1 = ?
				   AND ROUND(monto_temporal,2) >= " . $total;
        $result = $this->db->query($sql, array($pep1));
        return $result->row_array()['count'];
    }

    function insertarLogTracking($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $gics_sisego_db = $this->load->database('gics_sisego_db', TRUE);

            $gics_sisego_db->insert('log_tracking', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                throw new Exception('Error al insertar tabla log_tracking');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insertaron correctamente.';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function getUsuarioByIdUsuario($idUsuario)
    {
        $sql = " SELECT u.id_usuario,
                        UPPER(u.nombre) nombre,
                        u.cargo,
                        u.firma
                   FROM usuario u
                  WHERE u.id_usuario = ?
                  LIMIT 1";
        $result = $this->db->query($sql, array($idUsuario));
        return $result->row_array();
    }

    function getUsuarioUser($usuario)
    {
        $sql = " SELECT *
                   FROM usuario u
                  WHERE u.usuario = ?
                  LIMIT 1";
        $result = $this->db->query($sql, array($usuario));
        return $result->row_array();
    }

    /**
     * Insertar log de bandeja quiebre en gics_sisego_db
     */
    function insertLogBandejaQuiebre($arrBandeja)
    {
        $gics_sisego_db = $this->load->database('gics_sisego_db', TRUE);
        $gics_sisego_db->insert('log_bandeja_quiebre', $arrBandeja);

        if ($gics_sisego_db->affected_rows() == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Actualizar campos de planobra para log bandeja quiebre
     */
    function updatePlanObraQuiebre($data)
    {
        $sql = "UPDATE planobra AS a
                 JOIN ( 
                       SELECT CASE WHEN p.idProyecto != 21 AND  
                                        soc.tipo_solicitud NOT IN (3) AND 
                                        soc.estado = 1 
                                        THEN 'SI'
                                   WHEN po.idSubProyecto IN (663,670,665,671,707) AND 
                                        po.paquetizado_fg = 2 AND 
                                        po.idEmpresaColab IN (9,14) AND  
                                        soc.tipo_solicitud NOT IN (3) AND 
                                        soc.estado = 1
                                        THEN 'SI'
                                   WHEN p.idProyecto = 21 AND 
                                        sp.idTipoSubProyecto = 1 AND  
                                        soc.tipo_solicitud NOT IN (3) AND 
                                        soc.estado = 1
                                        THEN 'SI'
                             ELSE 'NO' END AS atencion_robot,
                             po.itemPlan
                    FROM proyecto p, 
                         subproyecto sp,
                         planobra po, 
                         solicitud_orden_compra soc,
                         itemplan_x_solicitud_oc ixs
                    WHERE sp.idSubProyecto = po.idSubProyecto 
                        AND po.itemplan = ixs.itemplan
                        AND ixs.codigo_solicitud_oc = soc.codigo_solicitud
                        AND sp.idProyecto = p.idProyecto
                        AND soc.codigo_solicitud = '" . $data['codigo_solicitud'] . "' LIMIT 1
                ) AS b ON a.itemPlan = b.itemPlan
        SET a.atencion_robot = b.atencion_robot,
            a.usuario_rechazo = " . (!empty($data['usuario_rechazo']) ? $data['usuario_rechazo'] : 'NULL') . ",
            a.fecha_rechazo = " . (!empty($data['fecha_rechazo']) ? "'" . $data['fecha_rechazo'] . "'" : 'NULL') . ",
            a.usuario_liberacion = " . (!empty($data['usuario_liberacion']) ? $data['usuario_liberacion'] : 'NULL') . ",
            a.fecha_liberacion = " . (!empty($data['fecha_liberacion']) ? "'" . $data['fecha_liberacion'] . "'" : 'NULL') . ",
            a.idMotivoQuiebre = " . (!empty($data['idMotivoQuiebre']) ? $data['idMotivoQuiebre'] : 'NULL') . ",
            a.comentarioQuiebre = " . (!empty($data['comentarioQuiebre']) ? "'" . $data['comentarioQuiebre'] . "'" : 'NULL') . "
            WHERE a.itemplan = '" . $data['itemplan'] . "'";

        $this->db->query($sql);

        _log($this->db->last_query());

        if ($this->db->affected_rows() > 0) {
            return array(
                'error' => EXIT_ERROR,
                'msj' => 'error al actualizar'
            );
        } else {
            return array(
                'error' => EXIT_SUCCESS,
                'msj' => 'error al actualizar'
            );
        }
    }

    public function existePep1enMargen($pep1)
    {

        $sql = "  SELECT COUNT(*) cantidad
				    FROM sap_detalle s,
						 pep1_margen pm
				   WHERE s.pep1= pm.pep1
                     AND pm.pep1 = ? ";

        $result = $this->db->query($sql, array($pep1));
        return $result->row_array()['cantidad'];
    }

    function getDataSubProyectoByItemplan($itemplan)
    {
        $sql = "  SELECT s.*
                    FROM planobra po,
                         subproyecto s
                   WHERE po.idSubProyecto = s.idSubProyecto
                     AND po.itemplan      = ?";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }

    function actualizarSolicitudCapex($codigo_solicitud, $dataSolicitud)
    {
        $this->db->where('codigo_solicitud', $codigo_solicitud);
        $this->db->update('solicitud_orden_compra', $dataSolicitud);
        if ($this->db->affected_rows() <= 0) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se actualizo la oc';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se actualizo correctamente!!';
        }

        return $data;
    }

    function getSolicitudCapexCount($itemplan, $tipo_solicitud, $estado)
    {
        $sql = "  SELECT COUNT(1) count
                    FROM solicitud_orden_compra s,
					     itemplan_x_solicitud_oc ixs
                   WHERE ixs.codigo_solicitud_oc = s.codigo_solicitud
				     AND ixs.itemplan = ?
					 AND s.tipo_solicitud = ?
					 AND s.estado = ?";
        $result = $this->db->query($sql, array($itemplan, $tipo_solicitud, $estado));
        return $result->row_array()['count'];
    }

    /**/
    function getEntidadEstadoAll()
    {
        $sql = "SELECT idEntidadEstado,
                           entidadEstadoDesc,
                           estado	
                      FROM entidad_estado
                     WHERE estado = 1;";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getEntidadTipoEvidenciaAll()
    {
        $sql = "SELECT idEntidadTipoEvidencia, 
                           descripcionTipoEvidencia 
                      FROM entidad_tipo_evidencia 
                     WHERE estado = 1;";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getCompromisoUsuarioAll()
    {
        $sql = "SELECT idUsuarioCompromiso, 
                           nombreUsuarioCompromiso, 
                           estado 
                      FROM compromiso_usuario 
                     WHERE estado = 1;";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getCompromisoEstadoAll()
    {
        $sql = "SELECT idEstadoCompromiso,
                           compromisoEstadoDesc,
                           estado
                      FROM compromiso_estado
                     WHERE estado = 1";
        $result = $this->db->query($sql);

        return $result->result_array();
    }

    public function getGerenciaFirmaDigitalAll()
    {
        $sql = "SELECT idGerencia,
                       gerenciaDesc,
                       idEstado	
                  FROM gerencia
                 WHERE flgFirma = 1
                   AND idEstado = 1;";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function getUsuarioByFirmaDigital($isTdp)
    {
        $sql = "SELECT t1.id_usuario,
                       UPPER(t1.usuario) usuario,
                       UPPER(t1.nombre) nombre,
                       t1.id_eecc,
                       t2.empresaColabDesc
                  FROM usuario t1,
                       empresacolab t2,
					   usuario_x_rol uxr
                 WHERE t1.id_eecc = t2.idEmpresaColab
				   AND t1.id_usuario = uxr.idUsuario
                   AND CASE WHEN ? = 1 THEN t1.id_eecc IN (0, 6) ELSE t1.id_eecc NOT IN (0, 6) END
				 GROUP BY id_usuario 
              ORDER BY t1.usuario";
        $result = $this->db->query($sql, [$isTdp]);
        return $result->result_array();
    }

    public function eliminarSubproyectoValidaActa($idSubProyecto)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idSubProyecto', $idSubProyecto);
            $this->db->delete('usuario_x_subproyecto_valida_acta');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se eliminó correctamente!!.';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error al eliminar');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function insertarSubproyectoValidaActa($arrData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('usuario_x_subproyecto_valida_acta', $arrData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla usuario_x_subproyecto_valida_acta');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getUsuarioFirmaBySubproyecto($idSubProyecto)
    {
        $sql = "SELECT t1.idSubProyecto,
                       t1.idUsuario,
                       UPPER(CONCAT_WS(' ', t2.nombres, t2.ape_paterno, t2.ape_materno)) AS nombre,
                       t1.idRol,
                       t3.rolDesc
                  FROM usuario_x_subproyecto_valida_acta t1,
                       usuario t2,
                       rol t3
                 WHERE t1.idUsuario = t2.id_usuario
                   AND t1.idRol = t3.idRol
                   AND t1.idSubProyecto = ?;";
        $result = $this->db->query($sql, [$idSubProyecto]);
        return $result->result_array();
    }


    public function registrarFirmaEmpresaColab($dataFirmaEmpresaColab)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('firma_empresacolab', $dataFirmaEmpresaColab);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla firma_empresacolab');
            } else {
                $firma_empresacolab = $this->db->insert_id();
                $this->db->trans_commit();
                $data['idFirmaEmpresacolab'] = $firma_empresacolab;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registró correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function eliminarFirmaEmpresaColab($idEmpresaColab, $idUsuario)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idEmpresaColab', $idEmpresaColab);
            $this->db->where('idUsuario', $idUsuario);
            $this->db->delete('firma_empresacolab');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se eliminó correctamente!!.';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error al eliminar');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getFirmaEmpresaColabAll($idEmpresaColab, $idUsuario)
    {
        $sql = "SELECT
                        t1.idEmpresaColab,
                        t2.empresaColabDesc,
                        t1.idTipoPlanta,
                        t3.tipoPlantaDesc,
                        t1.idUsuario,
                        t4.usuario,
                        CONCAT_WS( ' ', t4.nombres, t4.ape_paterno, t4.ape_materno ) AS nombreUsuario,
                        t1.idRol,
                        t5.rolDesc,
                        t1.fechaRegistro,
                        t1.estado,
                        GROUP_CONCAT(
                            DISTINCT CONCAT(t1.idGerencia) 
                            ORDER BY t6.idGerencia
                            SEPARATOR ';'
                        ) group_gerencias 
                FROM
                        ( firma_empresacolab t1, empresacolab t2, tipoplanta t3, usuario t4, rol t5 )
                        LEFT JOIN gerencia t6 ON t1.idGerencia = t6.idGerencia
                WHERE
                        t1.idEmpresaColab = t2.idEmpresaColab 
                        AND t1.idTipoPlanta = t3.idTipoPlanta 
                        AND t1.idUsuario = t4.id_usuario 
                        AND t1.idRol = t5.idRol
                        AND t1.idEmpresaColab = COALESCE(?, t1.idEmpresaColab)
                        AND t1.idUsuario = COALESCE(?, t1.idUsuario)
                GROUP BY
                t1.idEmpresaColab,
                t2.empresaColabDesc,
                t1.idTipoPlanta,
                t1.idRol,
                t5.rolDesc,
                t1.fechaRegistro,
                t1.estado,
                t4.nombres, 
                t4.ape_paterno, 
                t4.ape_materno,
                t3.tipoPlantaDesc,
                t1.idUsuario";
        $result = $this->db->query($sql, [$idEmpresaColab, $idUsuario]);
        return $result->result_array();
    }
	
	    function getDataReembolsoByItem($itemplan, $flg_validado, $estado) {
        $sql = " SELECT t.*
                   FROM (  
                 
                            SELECT i.itemplan,
                                    i.idEntidad,
                                    fecha_valida,
                                    uv.nombre,
                                    e.estadoPlanDesc,
                                    su.subProyectoDesc,
                                    GROUP_CONCAT(DISTINCT en.desc_entidad) group_entidad,
                                    FORMAT(ROUND(SUM(i.monto_fin_reembolso), 2), 2) as monto,
                                    SUM(CASE WHEN flg_valida_reembolso = 1 THEN 1
                                            ELSE 0 END) as count_validado,
                                    SUM(CASE WHEN flg_valida_reembolso IS NULL THEN 1
                                            ELSE 0 END) as count_pendiente_validado,
                                    COUNT(*) AS total_casos,
                                    flg_valida_reembolso,
                                    pro.proyectoDesc,
                                    prom.nom_promotor,
                                    z.zonalDesc,
                                    er.estadoDesc as estado_reembolso,
                                    ec.empresaColabDesc
                               FROM itemplan_estacion_licencia_det i,
                                    reembolso r,
                                    planobra po,
                                    usuario uv,
                                    estadoplan e,
                                    subproyecto su,
                                    entidad en,
                                    (SELECT iel.itemplan, 
                                            SUM(CASE WHEN flg_validado <> 2 THEN 1 ELSE 0 END) as sum_no_validado
                                       FROM itemplan_estacion_licencia_det iel
                                      WHERE flg_tipo NOT IN (1,3)
                                      GROUP BY itemplan
                                      HAVING sum_no_validado = 0)t,
                                    proyecto pro,
                                    promotor prom,
                                    zonal z,
                                    estado_reembolso er,
                                    empresacolab ec
                            WHERE i.iditemplan_estacion_licencia_det = r.iditemplan_estacion_licencia_det
                                AND po.idEstadoReembolso = er.id_estado_reembolso
                                AND z.idZonal  = po.idZonal
                                AND t.itemplan = i.itemplan
                                AND su.idSubProyecto = po.idSubProyecto
                                AND i.itemplan   = COALESCE(?, i.itemplan)
                                AND po.idEstadoReembolso = COALESCE(?, po.idEstadoReembolso)
                                AND i.flg_validado = ?
                                AND i.id_usuario_valida = uv.id_usuario
                                AND po.itemplan = i.itemplan
                                AND po.idEstadoPlan = e.idEstadoPlan
                                AND en.idEntidad = i.idEntidad
                                AND (CASE WHEN po.flg_exception_reembolso IS NULL THEN po.idEstadoPlan IN (4,22,23) ELSE po.flg_exception_reembolso = '1' END)
                                #AND i.id_promotor IS NOT NULL
                                AND su.idProyecto = pro.idProyecto
                                AND prom.id_promotor = su.id_promotor
                                AND po.idEmpresaColab = ec.idEmpresaColab
                                AND i.flg_tipo NOT IN (1,3)
                                #AND NOT EXISTS(SELECT 1 
                                #                 FROM itemplan_x_solicitud_oc_reembolso ixc
                                #                WHERE ixc.itemplan = i.itemplan)
                                GROUP BY i.itemplan  
                    )t";
        $result = $this->db->query($sql, array($itemplan, $estado, $flg_validado));
        return $result->result_array();
    }
	
	    function getDetalleReembolso($itemplan, $flg_validado) {
        $sql = " SELECT i.itemplan,
                        i.idEntidad,
                        fecha_valida,
                        uv.nombre,
                        e.estadoPlanDesc,
                        SUM(r.monto) AS monto,
                        en.desc_entidad,
                        pro.nom_promotor,
                        i.flg_valida_reembolso,
                        r.ruta_foto,
                        SUM(i.monto_fin_reembolso) AS monto_fin,
                        i.iditemplan_estacion_licencia_det AS id_licencia_entidad,
                        po.idEmpresaColab,
                        ec.empresaColabDesc,
                        po.idEstadoReembolso,
                        r.desc_reembolso AS nro_reembolso,
                        i.flg_sol_reembolso,
                        (CASE WHEN i.flg_sol_reembolso IS NULL THEN 'SIN PROCESAR POR EL ALGORITMO'
                              WHEN i.flg_sol_reembolso = '0' THEN 'NO SE GENERO POR FALTA DE PPT'
                              WHEN i.flg_sol_reembolso = '1' THEN 'SE GENERO LA SOLICITUD'
                              WHEN i.flg_sol_reembolso = '2' THEN 'SE ATENDIO LA SOLICITUD'
                              WHEN i.flg_sol_reembolso = '3' THEN 'PERTENECIO A LA SOLICITUD, PERO SE DIO DE BAJA'
                          ELSE '' END)desc_estado_reembolso,
                        (CASE WHEN i.flg_sol_reembolso IS NULL THEN 'EN PROCESO'
                              WHEN i.flg_sol_reembolso IN ('0','3') THEN 'SIN PRESUPUESTO'
                              WHEN i.flg_sol_reembolso IN ('1') THEN 'CON PRESUPUESTO'
                              WHEN i.flg_sol_reembolso IN ('2') THEN ''
                        ELSE '' END) situacion
                   FROM itemplan_estacion_licencia_det i,
                        reembolso r,
                        planobra po,
                        usuario uv,
                        estadoplan e,
                        entidad en,
                        promotor pro,
                        subproyecto sp,
                        empresacolab ec
                  WHERE i.iditemplan_estacion_licencia_det = r.iditemplan_estacion_licencia_det
                    AND i.itemplan   = COALESCE(?, i.itemplan)
                    AND flg_validado = ?#que tiene reembolso
                    AND i.id_usuario_valida = uv.id_usuario
                    AND po.itemplan = i.itemplan
                    AND po.idEstadoPlan = e.idEstadoPlan
                    AND en.idEntidad = i.idEntidad
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND pro.id_promotor = sp.id_promotor
                    AND po.idEmpresaColab = ec.idEmpresaColab
                    AND i.flg_tipo NOT IN (1,3)
                    AND (CASE WHEN po.flg_exception_reembolso IS NULL THEN po.idEstadoPlan IN (4,22,23) ELSE po.flg_exception_reembolso = '1' END)
                GROUP BY i.iditemplan_estacion_licencia_det";
        $result = $this->db->query($sql, array($itemplan, $flg_validado));
        return $result->result_array();
    }
	
	function updateFlgValidaReembolsoLicencia($arrayJson, $idUsuario, $fechaRegistro) {
        $flgResp = 1;
        
        foreach($arrayJson as $row) {
            $this->db->where('iditemplan_estacion_licencia_det' , $row['id_licencia_entidad']);
            $this->db->update('itemplan_estacion_licencia_det', array(
                                                                        'flg_valida_reembolso'        => $row['flg_valida_reembolso'],
                                                                        'id_usuario_valida_reembolso' => $idUsuario,
                                                                        'fecha_valida_reembolso'      => $fechaRegistro,
                                                                        'monto_fin_reembolso'         => $row['monto'],
                                                                        'nro_reembolso'               => $row['nro_reembolso'],
                                                                        'ruta_reembolso_validado'     => $row['ruta_reembolso']
                                                                    ));
            if ($this->db->affected_rows() == 0) {
                $flgResp = 0;
            }
        }

        if($flgResp == 0) {
            $data['msj']   = 'No se pudo validar.';
            $data['error'] = EXIT_ERROR;
            //$this->db->trans_rollback();
        } else {
            $data['msj']   = 'Se validaron las entidades correctamente.';
            $data['error'] = EXIT_SUCCESS;
            // $this->db->trans_commit();
        }
        
        return $data;
    }
	
	function getEstadoValidaReembolso($itemplan) {
        $sql = "SELECT CASE WHEN count_validado = 0 AND count_pendiente_validado > 0 THEN 1
                            WHEN count_validado > 0 AND count_pendiente_validado > 0 THEN 2
                            WHEN count_validado > 0 AND count_pendiente_validado = 0 THEN 3 END as estado_reembolso
                  FROM 
                        (SELECT i.itemplan,
                                i.idEntidad,
                                fecha_valida,
                                SUM(CASE WHEN flg_valida_reembolso = 1 THEN 1
                                        ELSE 0 END) as count_validado,
                                SUM(CASE WHEN flg_valida_reembolso IS NULL THEN 1
                                        ELSE 0 END) as count_pendiente_validado       
                        FROM itemplan_estacion_licencia_det i
                        WHERE i.itemplan   = COALESCE(?, i.itemplan)
                          AND flg_validado = 2
                          AND flg_tipo NOT IN (1,3)#COMUNICATIVA Y EIA NO HACEN REEMBOLSO
                        GROUP BY i.itemplan)t";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['estado_reembolso'];
    }
	
	function getEstadoReembolsoAll() {
        $sql = "SELECT id_estado_reembolso,
                       estadoDesc,
                       estado
                  FROM estado_reembolso
                 WHERE estado = 1";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
	
	    function actualizarEstadoSolicitudCapexByItemplan($itemplan, $tipo_solicitud, $estado)
    {
        $sql = "UPDATE planobra po,
					   itemplan_x_solicitud_oc ixs,
					   solicitud_orden_compra s
				   SET s.estado = ?
				 WHERE po.itemplan = ixs.itemplan
				   AND ixs.codigo_solicitud_oc = s.codigo_solicitud
				   AND s.tipo_solicitud = ?
				   AND s.estado = 1
				   AND ixs.itemplan = ?";
        $this->db->query($sql, array($estado, $tipo_solicitud, $itemplan));

        if ($this->db->trans_status() === FALSE) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se Actualizo!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se Actualizo correctamente!';
        }
        return $data;
    }
	
	function getLogFirmaDigital($itemplan){
	    $sql = "SELECT 'FIRMA DIGITAL' as tipo,
					   null,
					   ixs.itemplan,
					   lg.codigo_solicitud,
					   u.usuario,
						UPPER(u.nombre) nombre,
						lg.fechaRegistro as fecha,
						e.estadoFirmaDesc,
						lg.comentario
					FROM log_solicitud_firma lg,
						usuario u,
						estado_firma e,
						itemplan_x_solicitud_oc ixs
					WHERE lg.idUsuario = u.id_usuario
					AND e.idEstadoFirma = lg.idEstadoFirma
					AND ixs.codigo_solicitud_oc = lg.codigo_solicitud
					AND ixs.itemplan = ?
				ORDER BY lg.fechaRegistro DESC" ;
	    $result = $this->db->query($sql,array($itemplan));
	    return $result->result();
	}
	
	function getLogFirmaDigitalAll($itemplan){
	    $sql = " SELECT t.codigo_solicitud, 
                           CASE WHEN t.nombre IS NULL THEN null
                                ELSE t.fecha END fecha,
                           UPPER(t.nombre) nombre,
                           t.tipo,
                           t.estado,
                           UPPER(u.nombre) as responsable 
                      FROM (    
                         SELECT soc.codigo_solicitud,
				          		(SELECT fechaRegistro 
								   FROM log_solicitud_firma lg
								  WHERE lg.codigo_solicitud = soc.codigo_solicitud
									AND lg.idEstadoFirma = 4
									 limit 1) fecha,
				                 u.nombre,
				                  'GERENTE' as tipo,
				                 CASE WHEN soc.idUsuarioFirmaGerente  IS NULL THEN 'PENDIENTE'
				                      ELSE 'ATENDIDA' END estado,
							     4 id_tipo,
                                 soc.idEmpresaColab,
                                 soc.idSubProyecto 
				            FROM (solicitud_orden_compra soc,
				            	 itemplan_x_solicitud_oc ixso)
				       LEFT JOIN usuario u
				              ON soc.idUsuarioFirmaGerente = u.id_usuario
				           WHERE ixso.itemplan = ?
                             AND soc.codigo_solicitud = ixso.codigo_solicitud_oc
                             AND soc.tipo_solicitud = 3
					UNION ALL
					      SELECT soc.codigo_solicitud,
				          		(SELECT fechaRegistro 
								   FROM log_solicitud_firma lg
								  WHERE lg.codigo_solicitud = soc.codigo_solicitud
									AND lg.idEstadoFirma = 3
									 limit 1) fecha,
				                 u.nombre,
				                 'JEFE TDP',
				                 CASE WHEN soc.idUsuarioFirmaJefeTdp IS NULL THEN 'PENDIENTE'
				                      ELSE 'ATENDIDA' END estado,
								 3,
                                 soc.idEmpresaColab,
                                 soc.idSubProyecto 
				            FROM (solicitud_orden_compra soc,
				            	 itemplan_x_solicitud_oc ixso)
				       LEFT JOIN usuario u
				              ON soc.idUsuarioFirmaJefeTdp = u.id_usuario
				           WHERE ixso.itemplan = ?
                             AND soc.codigo_solicitud = ixso.codigo_solicitud_oc
                             AND soc.tipo_solicitud = 3

					UNION ALL

						 SELECT soc.codigo_solicitud,
				          		(SELECT fechaRegistro 
								   FROM log_solicitud_firma lg
								  WHERE lg.codigo_solicitud = soc.codigo_solicitud
									AND lg.idEstadoFirma = 2
									 limit 1) fecha,
				                 u.nombre,
				                 'SUPERVISOR',
				                 CASE WHEN soc.idUsuarioFirmaSup IS NULL THEN 'PENDIENTE'
				                      ELSE 'ATENDIDA' END estado,
								 2,
                                 soc.idEmpresaColab,
                                 soc.idSubProyecto 
				            FROM (solicitud_orden_compra soc,
				            	 itemplan_x_solicitud_oc ixso)
				       LEFT JOIN usuario u
				              ON soc.idUsuarioFirmaSup = u.id_usuario
				           WHERE ixso.itemplan = ?
							 AND soc.codigo_solicitud = ixso.codigo_solicitud_oc
                             AND soc.tipo_solicitud = 3
					UNION ALL
				          SELECT soc.codigo_solicitud,
				          		(SELECT fechaRegistro 
								   FROM log_solicitud_firma lg
								  WHERE lg.codigo_solicitud = soc.codigo_solicitud
									AND lg.idEstadoFirma = 1
									 limit 1) fecha,
				                 u.nombre,
				                 'EECC',
				                 CASE WHEN soc.idUsuarioFirmaJefeEmp IS NULL THEN 'PENDIENTE'
				                      ELSE 'ATENDIDA' END estado,
								 1,
                                 soc.idEmpresaColab,
                                 soc.idSubProyecto 
				            FROM (solicitud_orden_compra soc,
				                 itemplan_x_solicitud_oc ixso)
				       LEFT JOIN usuario u
				              ON soc.idUsuarioFirmaJefeEmp = u.id_usuario
				           WHERE ixso.itemplan = ?
				             AND soc.codigo_solicitud = ixso.codigo_solicitud_oc 
				             AND soc.tipo_solicitud = 3)t
            LEFT JOIN (usuario_x_rol uxr, usuario u, usuario_x_subproyecto_valida_acta uxv)
                   ON uxr.idUsuario = u.id_usuario AND uxv.idUsuario     = u.id_usuario 
				  AND CASE WHEN id_tipo = 1 THEN t.idEmpresaColab = u.id_eecc AND uxr.idRol = 3 
                           WHEN id_tipo = 2 THEN uxv.idRol = 4 AND uxv.idSubProyecto = t.idSubProyecto
                           WHEN id_tipo = 3 THEN uxv.idRol = 2 AND uxv.idSubProyecto = t.idSubProyecto 
                           WHEN id_tipo = 4 THEN uxv.idRol = 1 AND uxv.idSubProyecto = t.idSubProyecto END
             GROUP BY t.codigo_solicitud, tipo, responsable
			 ORDER BY id_tipo";
	    $result = $this->db->query($sql, array($itemplan, $itemplan, $itemplan, $itemplan));
	    return $result->result_array();
	}
	
	function insertLogTdpConsulta($jsonData)
    {
        $this->db->insert('log_consulta_tdp', $jsonData);
    }
	
	function getItemplanCertiEscaneoByFecha() {
        $sql = "SELECT itemplan 
                  FROM planobra
                 WHERE DATE(fecha_certifica) >= '2022-08-01' ";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
	
	function insertValidaEvidenciaIP($arrayData) {
        $this->db->insert('itemplan_valida_evidencia', $arrayData);
    }
	
	function getZona1_2()
    {
        $sql = "   SELECT id_zona,
						  nombre as zonaDesc
					 FROM zona 
					WHERE flg_contrato = 2";
        $result = $this->db->query($sql, array());
        return $result->result_array();
    }
	
	
    function simpleUpdateEstadoPlanObraMasDespliegue($transporte_db, $itemplan, $dataUpdate) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $transporte_db->where('itemPlan', $itemplan);
            $transporte_db->update('planobra', $dataUpdate);
            
            if ($transporte_db->affected_rows() <= 0) {
                throw new Exception('Error al modificar el updateEstadoPlanObra');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se Actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
	
	
    function actualizarSolicitudOcByItemplanMasDepliegue($transporte_db, $itemplan, $estadoAcual, $estadoUpd) {
        $sql = "UPDATE solicitud_orden_compra s, 
                       itemplan_x_solicitud_oc ixs
                   SET s.estado = ?
                 WHERE s.codigo_solicitud = ixs.codigo_solicitud_oc
                   AND s.estado = ?
                   AND ixs.itemplan = ?";
        $transporte_db->query($sql, array($estadoUpd, $estadoAcual, $itemplan));
        if ($transporte_db->trans_status() === FALSE) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se actualizo la oc';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se actualizo correctamente!!';
        }
        return $data;
    }
	
	
    function actualizarSolicitudCapexMasDepliegue($transporte_db, $codigo_solicitud, $dataSolicitud) {
        $transporte_db->where('codigo_solicitud', $codigo_solicitud);
        $transporte_db->update('solicitud_orden_compra', $dataSolicitud);
        if ($transporte_db->affected_rows() <= 0) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se actualizo la oc';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se actualizo correctamente!!';
        }

        return $data;
    }
	
	function getZonaByDesc($zonaDesc)
    {
        $sql = " SELECT id_zona,
		                nombre,
						alias,
						descripcion,
						flg_contrato
	    		   FROM zona
				  WHERE UPPER(nombre) = UPPER(?)";
        $result = $this->db->query($sql, array($zonaDesc));
        return $result->row_array();
    }
	
	function getPartidaZonaEm($partidaDesc, $idZona, $idEmpresaColab)
    {
        $sql = "SELECT id_partida, 
					   idZona, 
					   idEmpresaColab, 
					   costo 
				  FROM partida_x_zona_x_empresacolab_x_subproyecto pxs,
					   partidas pa
				 WHERE pa.idActividad = pxs.id_partida
				   AND UPPER(pa.descripcion) = UPPER(?)
				   AND idEmpresaColab = ?
				GROUP BY id_partida, idZona, idEmpresaColab";
        $result = $this->db->query($sql, array($partidaDesc, $idEmpresaColab));
        return $result->row_array();
    }
	
	function getPartidaByDesc($partidaDesc)
    {
        $sql = "SELECT idActividad
				  FROM partidas pa
				 WHERE UPPER(pa.descripcion) = UPPER(?)
				LIMIT 1";
        $result = $this->db->query($sql, array($partidaDesc));
		_log($this->db->last_query());
        return $result->row_array()['idActividad'];
    }
	
	function insertPartidaSbeMasivo($arrayData) {
		$this->db->insert_batch('partida_x_zona_x_empresacolab_x_subproyecto', $arrayData);
		if ($this->db->affected_rows() > 0) {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insertó correctamente!';
        } else {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'Error al insertar en la tabla.';
        }
        return $data;
	}
	
	function getCountPartidaSBE($idPartida, $idZona, $idEmpresaColab, $idSubProyecto)
    {
        $sql = "SELECT COUNT(1) count
				  FROM partida_x_zona_x_empresacolab_x_subproyecto pxs,
					   partidas pa
				 WHERE pa.idActividad = pxs.id_partida
				   AND pxs.id_partida = ?
				   AND idZona         = ?
				   AND idEmpresaColab = ?
				   AND idSubProyecto  = ?";
        $result = $this->db->query($sql, array($idPartida, $idZona, $idEmpresaColab, $idSubProyecto));
        return $result->row_array()['count'];
    }

    function getDataByCodigoUnicoSam($db, $codigo_unico) {
        $sql = " SELECT LatitudDefinitiva AS latitud, 
                        LongitudDefinitiva AS longitud,
                        d.Nombre AS distrito,
                        p.Nombre AS provincia,
                        de.Nombre AS departamento
                   FROM matrizseguimiento m,
                        distrito d,
                        provincia p,
                        departamento de
                  WHERE CodigoUnico = ?
                    AND d.IdDistrito = m.IdDistrito
                    AND d.IdProvincia = p.IdProvincia
                    AND de.IdDepartamento = p.IdDepartamento
                UNION ALL
                 SELECT latitud,
                        longitud,
                        d.Nombre AS distrito,
                        p.Nombre AS provincia,
                        de.Nombre AS departamento
                    FROM estacionconsolidado e,
                        distrito d,
                        provincia p,
                        departamento de
                  WHERE CodigoUnico = ?
                    AND d.IdDistrito = e.IdDistrito
                    AND d.IdProvincia = p.IdProvincia
                    AND de.IdDepartamento = p.IdDepartamento
                LIMIT 1";
            $result = $db->query($sql, array($codigo_unico, $codigo_unico));
            return $result->row_array();
    }

    function actualizarObraMasiva($dataUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->update_batch('planobra', $dataUpdate, 'itemplan');
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error al modificar el updateEstadoPlanObra');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se Actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
	
	function getPartidasActaDetalle($codigo_solicitud) {
		$sql = "SELECT ixs.itemplan,
					   ppo.ptr as codigo_po,
					   pa.codigo,
					   UPPER(pa.descripcion) partidaDesc,
					   ppd.cantidad_final as cantidad,
					   ppd.precio as costo,
					   ppd.baremo,
					   ppd.total
			      FROM ptr_x_actividades_x_zonal ppd,
					   partidas pa,
					   itemplan_x_solicitud_oc ixs,
					   ptr_planta_interna ppo
				 WHERE ppd.id_actividad = pa.idActividad
				   AND ixs.itemplan = ppd.itemplan
				   AND ppo.ptr = ppd.ptr
				   AND rangoPtr <> 6
				   AND ixs.codigo_solicitud_oc = ?";
		$result = $this->db->query($sql, array($codigo_solicitud));
        return $result->result_array();
	}
	
	function getSiomObraEvidencia($itemplan) {
		$sql = "SELECT itemplan,
		               estacionDesc, 
		               url_pdf_pruebas, 
					   url_pdf_perfil,
					   s.path_pdf_pruebas,
					   s.path_pdf_perfil
		          FROM siom_obra_evidencias s,
				       estacion e
				 WHERE e.idEstacion = s.idEstacion
				   AND s.itemplan = ?
				GROUP BY s.itemplan, s.idEstacion";
		$result = $this->db->query($sql, array($itemplan));
        return $result->result_array();
	}
	
	function getCountPoMasDesp($itemplan)
    {
        $sql = "SELECT COUNT(1) count
		          FROM ptr_planta_interna 
				 WHERE rangoPtr <> 6
				   AND itemplan ='" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row()->count;
    }
	
	function getPromotorAllSubProyecto($estado, $idSubProyecto = null)
    {
        $sql = "SELECT p.id_promotor,
					   nom_promotor,
					   s.idSubProyecto,
					   CASE WHEN ? = s.idSubProyecto THEN 1 
					        ELSE 0 END flg_selected
				  FROM promotor p
			 LEFT JOIN subproyecto s
					ON p.id_promotor = s.id_promotor
				 WHERE p.estado = ?
				GROUP BY p.id_promotor";
        $result = $this->db->query($sql, array($idSubProyecto, $estado));
        return $result->result_array();
    }
	
	function getEstadoFirmaAll($estado)
    {
        $sql = "SELECT idEstadoFirma,
                       estadoFirmaDesc
                  FROM estado_firma
                 WHERE estado = COALESCE(1, estado)";
        $result = $this->db->query($sql, array($estado));
        return $result->result_array();
    }
	
	function actualizarSolicitudCertiPdt($itemplan, $idUsuario, $fecha) {
		$sql = "UPDATE planobra po, 
					   itemplan_x_solicitud_oc ixso, 
					   solicitud_orden_compra soc 
				   SET soc.estado = 1,
                       soc.usuario_to_pndte = ".$idUsuario.",
					   soc.fecha_to_pndte   = '".$fecha."',
					   po.estado_oc_certi   = 'PENDIENTE'
				 WHERE po.itemPlan = ixso.itemplan 
				   AND ixso.codigo_solicitud_oc = soc.codigo_solicitud 
				   AND ixso.itemplan      = ?
				   AND soc.tipo_solicitud = 3
				   AND soc.estado = 5";
		$result = $this->db->query($sql, array($itemplan));
        if ($this->db->trans_status() === FALSE) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'No se Actualizo!';
        } else {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se Actualizo correctamente!';
        }
        return $data;
	}
	
	function getCountSolEdicionByItemplan($itemplan) {
		$sql = "SELECT COUNT(1) count 
		          FROM planobra po, 
				       itemplan_x_solicitud_oc ixso, 
					   solicitud_orden_compra soc 
				 WHERE po.itemPlan = ixso.itemplan 
				   AND ixso.codigo_solicitud_oc = soc.codigo_solicitud 
				   AND ixso.itemplan = ?
				   AND soc.tipo_solicitud = 2
				   AND soc.estado <> 3";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];
	}
}
