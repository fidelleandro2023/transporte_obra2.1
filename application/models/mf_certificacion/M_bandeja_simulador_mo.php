<?php
class M_bandeja_simulador_mo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaSimulador($criterios, $promotor) {
        $Query = "SELECT tb.proyectoDesc, tb.pep1, tb.total_po, sd.monto_temporal as monto_inicial, tb.total_po_si, tb.casteado_si, (sd.monto_temporal - tb.casteado_si) as disponible_si,
                        tb.total_po_no, tb.casteado_no
                        FROM
                        (
                        select p.proyectoDesc, pep1, 
                        count(1) as total_po,
                        sum((CASE WHEN estado = 1 THEN 1 ELSE 0 END)) as total_po_si,
                        CAST(sum((CASE WHEN estado = 1 THEN monto_mo ELSE 0 END)) AS DECIMAL(20,2)) as casteado_si, 
                        sum((CASE WHEN estado = 2 THEN 1 ELSE 0 END)) as total_po_no,
                        CAST(sum((CASE WHEN estado = 2 THEN monto_mo ELSE 0 END)) AS DECIMAL(20,2)) as casteado_no
                        from certificacion_mo_simulador cm, planobra po, subproyecto sp, proyecto p
                        where  cm.itemplan = po.itemplan
                        and po.idSubProyecto = sp.idSubProyecto
                        and sp.idProyecto = p.idProyecto
                        and cm.criterio in ?
                        and cm.estado in (1,2) ";
                if($promotor == 1){//EMPRESAS
                    $Query .= " and p.idProyecto = 3 ";
                }else if($promotor == 2){//RED
                    $Query .= " and p.idProyecto != 3 ";
                }
          $Query .= " group by p.proyectoDesc, pep1
                    ) tb JOIN sap_detalle sd on tb.pep1 = sd.pep1";
        $result = $this->db->query($Query, array($criterios));
        log_message('error', $this->db->last_query());
        return $result->result();           
    }

    /**antiguo**/
    public function getPtrsByHojaGestion($hoja_gestion)
    {
        $sql = "SELECT *,FORMAT(monto_mo,2) as monto_mo_format FROM certificacion_mo WHERE hoja_gestion = ?";
        $result = $this->db->query($sql,array($hoja_gestion));
        return $result->result();
    }
    
    function updateHojaGestion($idHojaGestion, $arrayData) {
        $this->db->where('id'  , $idHojaGestion);
        $this->db->update('bolsa_pep_hoja_gestion', $arrayData);    
        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $date['msj'] = 'Error al actualizar la Hoja Gestion';
            return $data;
        }else{
            $data['error'] = EXIT_SUCCESS;
            return $data;
        }
    }
    
    public function getBolsaHgDataByHG($id_hoja_gestion)
    {
        $sql = "SELECT * FROM bolsa_pep_hoja_gestion WHERE id = ?";
        $result = $this->db->query($sql,array($id_hoja_gestion));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
   
    public function getDataToExcelReport()
    {
        $sql = "SELECT p.proyectoDesc, sp.subProyectoDesc, cmo.areaDesc, cmo.itemplan, cmo.ptr, FORMAT(cmo.monto_mo,2) as monto_mo, cmo.orden_compra, cmo.nro_certificacion, cmo.pep1, bph.hoja_gestion, ec.empresaColabDesc, bph.cesta,
                    (CASE WHEN bph.tipo_obra = 1 THEN 'EMPRESAS'
                              WHEN bph.tipo_obra = 2 THEN 'RED'END) as tipoObraDesc,
                        (CASE WHEN bph.estado = 1 THEN 'RESERVADO'
                              WHEN bph.estado = 2 THEN 'EN PROCESO'
                              WHEN bph.estado = 3 THEN 'CERTIFICADO' END) as tipoEstadoDesc,
							  cmo.pep2
                    FROM empresacolab ec, bolsa_pep_hoja_gestion bph 
                    LEFT JOIN certificacion_mo cmo 
						JOIN planobra po ON cmo.itemplan = po.itemplan
                        JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
                        JOIN proyecto p ON sp.idProyecto = p.idProyecto
                    ON bph.hoja_gestion = cmo.hoja_gestion
                    WHERE ec.idEmpresaColab = bph.idEmpresaColab  
                    order by cmo.hoja_gestion";
        $result = $this->db->query($sql,array());
        return $result;
    }
   
}