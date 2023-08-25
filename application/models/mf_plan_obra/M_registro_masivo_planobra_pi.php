<?php
class M_registro_masivo_planobra_pi extends CI_Model{
	function __construct(){
		parent::__construct();
		
	}

    function getInfoProyectoByDesc($descripcion) {
        $sql = "  SELECT p.*,
                         (CASE WHEN p.proyectoDesc LIKE '%SISEGO%' THEN 1 ELSE 0 END) isSisego 
		            FROM proyecto p
				   WHERE REPLACE(p.proyectoDesc,' ', '') = REPLACE(?,' ', '') ";
        $result = $this->db->query($sql, array($descripcion));
        return $result->row_array();
    }

    function getInfoSubProyectoByDesc($descripcion) {
        $sql = "  SELECT * 
		            FROM subproyecto 
				   WHERE REPLACE(subproyectoDesc,' ', '') = REPLACE(?,' ', '')
				     AND estado = 1 ";
        $result = $this->db->query($sql, array($descripcion));
        return $result->row_array();
    }

    function getInfoFaseByDesc($descripcion) {
        $sql = "  SELECT * 
		            FROM fase 
				   WHERE REPLACE(faseDesc,' ', '') = REPLACE(?,' ', '') ";
        $result = $this->db->query($sql, array($descripcion));
        return $result->row_array();
    }

    function getInfoEECCByDesc($descripcion) {
        $sql = "  SELECT * 
		            FROM empresacolab 
				   WHERE REPLACE(empresaColabDesc,' ', '') = REPLACE(?,' ', '') ";
        $result = $this->db->query($sql, array($descripcion));
        return $result->row_array();
    }

    function getInfoContratoMarcoByDesc($contratoMarco) {
        $sql = " SELECT c.*,
                        cp.nombre AS nombre_contrato_padre,
                        cp.fecha_registro AS fec_reg_contrato_padre,
                        cp.estado AS estado_contrato_padre
                   FROM contrato c,
                        contrato_padre cp
                  WHERE c.id_contrato_padre = cp.id_contrato_padre
                    AND c.estado = 1
                    AND c.contrato_marco = ? ";
        $result = $this->db->query($sql, array($contratoMarco));
        return $result->row_array();
    }

    function getInfoCombinatoriaOpex($idSubProyecto,$idFase) {
        $sql = "     SELECT li.idlineaopex_fase,li.idlineaopex,lo.descripcion AS lineaopex_desc,
                            li.idfase,fa.faseDesc,
                            li.presupuesto,li.disponible_proyectado,li.monto_real,
                            slc.idSubProyecto,slc.idSubroLineaCom,slc.idcombinatoria,
                            co.ceco,co.cuenta,co.areafuncional
                       FROM lineaopex_fase li,
                            fase fa,
                            lineaopex lo,
                            subproyecto_lineaopex_combinatoria slc,
                            combinatoria_opex co
                      WHERE li.idfase = fa.idFase
                        AND li.idlineaopex = lo.idlineaopex
                        AND slc.idlineaopex_fase  = li.idlineaopex_fase
                        AND slc.idcombinatoria = co.idcombinatoria
                        AND slc.idSubProyecto = ?
                        AND li.idfase = ? ";
        $result = $this->db->query($sql, array($idSubProyecto,$idFase));
        return $result->row_array();
    }


    function getZonalxCentralPqt($idCentral) {
        $sql = "    SELECT c.*,z.zonalDesc
	    			  FROM pqt_central c, 
                           zonal z
					 WHERE c.idzonal = z.idzonal 
                       AND c.idcentral = ? ";
        $result = $this->db->query($sql, array($idCentral));
        return $result->row_array();
    }

	function insertarPlanobraPI($dataInsert){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
            $this->db->insert('planobra', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Error al insertar en la tabla plamobra');
	        }else{
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se insertó correctamente!';
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}

    function insertarLogPlanobraPI($dataInsert){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
            $this->db->insert('log_planobra', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Error al insertar en la tabla log_planobra');
	        }else{
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se insertó correctamente!';
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
}