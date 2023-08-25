<?php
class M_bandeja_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    /**21.06.2109 se agrego left join a itemplanestacionavance para obtener porcentaje**/
    function getBandejaSiom($idSubProyecto, $idEmpresaColab, $jefatura, $noEnviado, $itemplan) {
        $idEECC  = $this->session->userdata("eeccSession");
        $Query = " SELECT 
                        po.itemplan,po.indicador, pro.proyectoDesc, sp.subProyectoDesc, so.ptr, e.idEstacion, e.estacionDesc, ep.estadoPlanDesc, so.fechaRegistro, c.codigo, 
                        concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usua_registro, 
                        c.jefatura, ec.empresaColabDesc, so.codigoSiom, so.ultimo_estado, so.fecha_ultimo_estado, so.id_siom_obra, iea.porcentaje
                    FROM planobra po, subproyecto sp, proyecto pro, estadoplan ep, estacion e, central c, empresacolab ec, siom_obra so 
                    LEFT JOIN usuario u ON so.idUsuarioRegistro = u.id_usuario 
                    LEFT JOIN itemplanestacionavance iea ON iea.itemplan = so.itemplan AND iea.idEstacion = so.idEstacion
                    WHERE so.itemplan = po.itemplan
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND pro.idProyecto = sp.idProyecto
                    AND po.idCentral = c.idCentral
                    AND po.idEstadoPlan != ".ID_ESTADO_CANCELADO."
                    AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab
                    	      ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                    AND	so.idEstacion = e.idEstacion
                    AND po.idEstadoPlan = ep.idEstadoPlan
                    AND po.paquetizado_fg is null";
        
        if($idEECC == ID_EECC_QUANTA || $idEECC == ID_EECC_CAMPERU){
            $Query .= " AND  c.idEmpresaColabCV = ".$idEECC." AND sp.idTipoSubProyecto = 2 ";
        }else if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){
            $Query .= " AND  c.idEmpresaColab = ".$idEECC." AND sp.idTipoSubProyecto <> 2 ";
        }
        
        $Query .= ' AND po.idSubProyecto  = COALESCE(?, po.idSubProyecto)
                    AND c.jefatura        = COALESCE(?, c.jefatura)
                    AND ec.idEmpresaColab  = COALESCE(?, ec.idEmpresaColab)
                    AND UPPER(so.ultimo_estado)  = COALESCE(UPPER(?), so.ultimo_estado)
                    AND so.estado is null
                    AND po.itemplan = COALESCE(?, po.itemplan)';
        
        
        $Query .= " UNION ALL
                    SELECT
                        po.itemplan,po.indicador, pro.proyectoDesc, sp.subProyectoDesc, so.ptr, e.idEstacion, e.estacionDesc, ep.estadoPlanDesc, so.fechaRegistro, c.codigo,
                        concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usua_registro,
                        c.jefatura, ec.empresaColabDesc, so.codigoSiom, so.ultimo_estado, so.fecha_ultimo_estado, so.id_siom_obra, iea.porcentaje
                    FROM planobra po, subproyecto sp, proyecto pro, estadoplan ep, estacion e, pqt_central c, empresacolab ec, siom_obra so
                    LEFT JOIN usuario u ON so.idUsuarioRegistro = u.id_usuario
                    LEFT JOIN itemplanestacionavance iea ON iea.itemplan = so.itemplan AND iea.idEstacion = so.idEstacion
                    WHERE so.itemplan = po.itemplan
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND pro.idProyecto = sp.idProyecto
                    AND po.idCentralPqt = c.idCentral
                    AND po.idEstadoPlan != ".ID_ESTADO_CANCELADO."
                    AND po.idEmpresaColab = ec.idEmpresaColab
                    AND	so.idEstacion = e.idEstacion
                    AND po.idEstadoPlan = ep.idEstadoPlan
                    AND po.paquetizado_fg in (1,2)";
        
        if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){#validacion para que solo la eecc vea lo suyo
           $Query .= " AND po.idEmpresaColab = ".$idEECC;
        
        }
        
        $Query .= ' AND po.idSubProyecto  = COALESCE(?, po.idSubProyecto)
                    AND c.jefatura        = COALESCE(?, c.jefatura)
                    AND ec.idEmpresaColab  = COALESCE(?, ec.idEmpresaColab)
                    AND UPPER(so.ultimo_estado)  = COALESCE(UPPER(?), so.ultimo_estado)
                    AND so.estado is null
                    AND po.itemplan = COALESCE(?, po.itemplan)';
        $result = $this->db->query($Query, array($idSubProyecto, $jefatura, $idEmpresaColab, $noEnviado, $itemplan, $idSubProyecto, $jefatura, $idEmpresaColab, $noEnviado, $itemplan));
        //log_message('error', $this->db->last_query());
        return $result->result();           
    }

    function ingresarCodigoSiom($itemplan, $idEstacion, $arrayData) {
        $this->db->where('itemplan'  , $itemplan);
        $this->db->where('idEstacion'  , $idEstacion);        
        $this->db->update('siom_obra', $arrayData);

        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $date['msj'] = 'Error al ingresar codigo siom';
            return $data;
        }else{
            $data['error'] = EXIT_SUCCESS;
            return $data;
        }
    }
    
    public function getNodoByIdSiomObra($id_siom_obra)
    {
        $sql = "SELECT  so.id_siom_obra, c.idCentral, c.codigo
                FROM    siom_obra so,
                        planobra po,
                        central c
                WHERE   so.itemplan = po.itemplan
                AND     po.idCentral = c.idCentral
                AND	    so.id_siom_obra = ?
                AND 	(po.paquetizado_fg is null or po.paquetizado_fg = 1)
                UNION ALL
                SELECT  so.id_siom_obra, c.idCentral, c.codigo
                FROM    siom_obra so,
                        planobra po,
                        pqt_central c
                WHERE   so.itemplan = po.itemplan
                AND     po.idCentralPqt = c.idCentral
                AND	    so.id_siom_obra = ?
                and		po.paquetizado_fg = 2";
        $result = $this->db->query($sql,array($id_siom_obra, $id_siom_obra));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    public function listaNodosSiomPosibles()
    {
        $sql = "SELECT  c.idCentral, c.codigo, c.tipoCentralDesc  
                FROM    siom_nodo sn, central c
                WHERE   sn.empl_nemonico = c.codigo";
        $result = $this->db->query($sql,array());
        return $result->result();
    }
     public function listaLogEstadosSIom($codigo_siom)
    {
        $sql = "SELECT * FROM log_tramas_estados_siom WHERE codigo_siom = ?";
        $result = $this->db->query($sql,array($codigo_siom));
        return $result->result();
    }
    
    public function getDataSiomByIdSiomObra($id_siom_obra)
    {
        $sql = "SELECT so.*, CONCAT(u.nombres, ' ',u.ape_paterno, ' ', u.ape_materno) as nomCompleto
                FROM siom_obra so LEFT JOIN  usuario u ON so.idUsuarioRegistro = u.id_usuario where so.id_siom_obra = ?";
        $result = $this->db->query($sql,array($id_siom_obra));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    /**
     * METODO QUE OBTIENE LAS ESTACIONES QUE PUEDEN GENERAR UN REENVIO EN SIOM, EN BASE AH QUE TENGA PO MAT APROBADA
     * 21.06.2019 UM Y ATT NO NECESITAN PTR MAT PARA SER SELECCIONABLES
     * @param unknown $itemplan
     */
    public function getEstacionesToSendSiom($itemplan)
    {
        $sql = " SELECT tb.* , 
                        (SELECT COUNT(1) FROM planobra_po ppo WHERE ppo.itemplan = tb.itemplan
                            AND tb.idEstacion = ppo.idEstacion and ppo.estado_po IN (3,4,5,6) and ppo.flg_tipo_area = 1) as cant,
                        iea.porcentaje
                FROM (SELECT distinct po.itemplan, ea.idEstacion, e.estacionDesc
                FROM planobra po, subproyectoestacion se, estacionarea ea, estacion e
                WHERE po.idSubProyecto = se.idSubProyecto
                AND se.idEstacionArea = ea.idEstacionArea
                AND ea.idEstacion = e.idEstacion
                AND po.itemplan = ?
                AND ea.idEstacion IN (5,15,16,2,6,3,13,4,14)) AS tb LEFT JOIN itemplanestacionavance iea ON tb.itemplan = iea.itemplan AND tb.idEstacion = iea.idEstacion
                HAVING (CASE WHEN tb.idEstacion not in (3,6,13,14) THEN cant >= 1 ELSE TRUE END)";
        $result = $this->db->query($sql,array($itemplan));
        return $result->result();
    }
   
}