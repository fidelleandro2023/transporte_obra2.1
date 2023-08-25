<?php
class M_gestionar_hoja_gestion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaHojaGestion($tipoObra, $eecc, $estado, $hojaGestion, $codigoPo, $cesta) {
        $Query = " SELECT bph.id, bph.estado, bph.hoja_gestion, ec.empresaColabDesc, bph.cesta, bph.fecha_creacion, bph.orden_compra, bph.nro_certificacion,
                    (CASE WHEN bph.tipo_obra = 1 THEN 'EMPRESAS'
                              WHEN bph.tipo_obra = 2 THEN 'RED'END) as tipoObraDesc,
                        (CASE WHEN bph.estado = 1 THEN 'PRE RESERVADO'
                              WHEN bph.estado = 2 THEN 'RESERVADO'
                              WHEN bph.estado = 3 THEN 'EN PROCESO' 
                              WHEN bph.estado = 4 THEN 'CERTIFICADO' END) as tipoEstadoDesc,
                    FORMAT(bph.monto_limite,2) as limite_format, 
                    FORMAT(sum(case when cmo.monto_mo is not null then cmo.monto_mo else 0 end),2) as real_format, 
                    SUM(case when cmo.ptr is not null then 1 else 0 end) as num_ptr,
					bph.fecha_en_proceso, bph.fecha_certificacion
                    FROM empresacolab ec, bolsa_pep_hoja_gestion bph LEFT JOIN certificacion_mo cmo ON
                     bph.hoja_gestion = cmo.hoja_gestion
                    WHERE ec.idEmpresaColab = bph.idEmpresaColab
                    and bph.estado <> 1  ";
        
            if($tipoObra!=null){
                $Query .= " AND bph.tipo_obra = ".$tipoObra;
            }
            if($eecc!=null){
                $Query .= " AND bph.idEmpresaColab = ".$eecc;
            }
            if($estado!=null){
                $Query .= " AND bph.estado = ".$estado;
            }
            if($hojaGestion!=null){
                $Query .= " AND bph.hoja_gestion = '".trim($hojaGestion)."'";
            }
            if($cesta != null){
                $Query .= " AND bph.cesta = '".trim($cesta)."'";
            }
            if($codigoPo!=null){
                $Query .= " AND bph.hoja_gestion = (SELECT hoja_gestion FROM certificacion_mo WHERE ptr = '".trim($codigoPo)."')";
            }
            $Query .= ' group by bph.hoja_gestion';
        $result = $this->db->query($Query, array());
        #log_message('error', $this->db->last_query());
        return $result->result();           
    }

    public function getPtrsByHojaGestion($hoja_gestion)
    {
        $sql = "SELECT *,FORMAT(monto_mo,2) as monto_mo_format FROM certificacion_mo WHERE hoja_gestion = ?";
        $result = $this->db->query($sql,array($hoja_gestion));
        return $result->result();
    }
    
    function updateHojaGestionCertificacion($logPlanobraPoData, $arrayUpCertificacionMo, $arrayUpPlanobraPo, $arrayUpDetallePlan, $dataHojagestion, $idHojaGestion){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
                $this->db->trans_begin();        
                $this->db->insert_batch('log_planobra_po', $logPlanobraPoData);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al insertar en log_planobra_po.');
                }else{
                    $this->db->update_batch('certificacion_mo', $arrayUpCertificacionMo, 'ptr');
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Hubo un error al actualizar en certificacion_mo.');
                    }else{
                        $this->db->update_batch('planobra_po', $arrayUpPlanobraPo, 'codigo_po');
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Hubo un error al actualizar en planobra_po.');
                        }else{
                            $this->db->update_batch('detalleplan', $arrayUpDetallePlan, 'poCod');
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Hubo un error al actualizar en detalleplan.');
                            }else{
                                $this->db->where('id'  , $idHojaGestion);
                                $this->db->update('bolsa_pep_hoja_gestion', $dataHojagestion);
                                if($this->db->affected_rows() != 1) {
                                    $this->db->trans_rollback();
                                    throw new Exception('Error al actualizar la Hoja Gestion');
                                }else{
                                    $this->db->query("UPDATE certificacion_mo SET estado_validado = 0, hoja_gestion = NULL 
                                                        WHERE estado = 1 AND hoja_gestion = (SELECT hoja_gestion from bolsa_pep_hoja_gestion WHERE id = ".$idHojaGestion.")");
                                    if ($this->db->trans_status() === TRUE) {
                                        $this->db->query("UPDATE bolsa_pep_hoja_gestion set monto_real = (SELECT SUM(monto_mo) FROM certificacion_mo WHERE hoja_gestion = (SELECT tb.hoja_gestion FROM (SELECT hoja_gestion from bolsa_pep_hoja_gestion WHERE id = ".$idHojaGestion.") as  tb))  where id = ".$idHojaGestion);
                                        if ($this->db->trans_status() === TRUE) {
                                                $this->db->trans_commit();
                                                $data['error']    = EXIT_SUCCESS;
                                                $data['msj']      = 'Se inserto correctamente!';
                                            }else{
                                                throw new Exception('Error al actualizar monto real en bolsa_pep_hoja_gestion');
                                            }                                      
                                    }else{
                                        throw new Exception('Error al actualizar nulos en Certificacion');
                                    }
                                    
                                }                               
                            }
                        }                       
                    }                    
                }            
    
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            log_message('error', $e->getMessage());
            $this->db->trans_rollback();
        }
        log_message('error', 'query:'.print_r($data, true));
        return $data;
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
                        (CASE WHEN bph.estado = 1 THEN 'INICIADO'
							  WHEN bph.estado = 2 THEN 'RESERVADO'
                              WHEN bph.estado = 3 THEN 'EN PROCESO'
                              WHEN bph.estado = 4 THEN 'CERTIFICADO' END) as tipoEstadoDesc,
							  cmo.pep2, bph.fecha_en_proceso, bph.fecha_certificacion, bph.fecha_creacion
                    FROM empresacolab ec, bolsa_pep_hoja_gestion bph 
                    LEFT JOIN certificacion_mo cmo 
						JOIN planobra po ON cmo.itemplan = po.itemplan
                        JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
                        JOIN proyecto p ON sp.idProyecto = p.idProyecto
                    ON bph.hoja_gestion = cmo.hoja_gestion
                    WHERE ec.idEmpresaColab = bph.idEmpresaColab
					  AND bph.estado IN (2,3,4)
                    order by cmo.hoja_gestion";
        $result = $this->db->query($sql,array());
        return $result;
    }
    
    function updatePtrFromHojaGestion($ptr, $arrayData, $idHojaGestion, $arrayDataLog) {
        $this->db->where('ptr'  , $ptr);
        $this->db->update('certificacion_mo', $arrayData);
        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $date['msj'] = 'Error al actualizar romper la ptr de la Hoja de Gestion';
            return $data;
        }else{            
                $this->db->query("UPDATE bolsa_pep_hoja_gestion set monto_real = (SELECT SUM(monto_mo) FROM certificacion_mo WHERE hoja_gestion = (SELECT tb.hoja_gestion FROM (SELECT hoja_gestion from bolsa_pep_hoja_gestion WHERE id = ".$idHojaGestion.") as  tb)) where id = ".$idHojaGestion);
                if ($this->db->trans_status() === TRUE) {
					$this->db->insert('log_remove_ptr_hg', $arrayDataLog);
					if($this->db->affected_rows() != 1) {
						$this->db->trans_rollback();
						throw new Exception('Error al insertar en log_remove_ptr_hg');
					}else{               
						$data['error'] = EXIT_SUCCESS;
						$data['msj'] = 'Se actualizo correctamente!';
						$this->db->trans_commit();            
					}                    
                }else{
                    throw new Exception('Error al actualizar monto real en bolsa_pep_hoja_gestion');
                }
           
            $data['error'] = EXIT_SUCCESS;
            return $data;
        }
    }
   
}