<?php
class M_bandeja_ficha_tecnica_cv extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
function   getBandejaFichaTecnicaEvaluacionCV(){
	    $Query = "SELECT po.itemPlan, po.indicador ,sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc,
                            CASE
            					WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
            					WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
            					WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
            					WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
            					WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
            					WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
            					WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
            					WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
            					WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
            					WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
            					WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
            					WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
            				ELSE NULL
            				END as fechaPrevEjec, ft.id_ficha_tecnica, ft.estado_validacion, 
                            CASE WHEN ft.estado_validacion = '1' THEN 'APROBADO'
								 WHEN ft.estado_validacion = '2' THEN 'RECHAZADO' 
                                 ELSE 'PENDIENTE' END as estado_vali,
                            ft.id_ficha_tecnica_base, e.estacionDesc, ft.fecha_registro
			    FROM subproyecto sp, zonal z, empresacolab ec, estadoplan ep, estacion e, planobra po
				LEFT JOIN ficha_tecnica ft ON po.itemplan = ft.itemplan
                WHERE po.idSubProyecto = sp.idSubProyecto
                AND po.idZonal = z.idZonal
                AND po.idEmpresaColab = ec.idEmpresaColab
                AND po.idEstadoPlan = ep.idEstadoPlan
                AND ft.id_estacion = e.idEstacion
                AND po.idEstadoPlan IN('".ID_ESTADO_PRE_LIQUIDADO."', '".ID_ESTADO_TERMINADO."')
                AND ft.id_ficha_tecnica is not null 
                AND ft.flg_activo = 1
                AND po.idSubProyecto = ".ID_SUB_PROYECTO_CV_INTEGRAL." 
	            AND CASE WHEN  ft.estado_validacion NOT IN ('1','2') THEN po.has_sirope = 1 ELSE TRUE END
	            AND CASE WHEN po.fechaPreliquidacion > ft.fecha_registro 
                    THEN TIMESTAMPDIFF(HOUR,po.fechaPreliquidacion,NOW()) >= ".NUMERO_HORAS_EDITAR_MATERIALES_CV." 
                    ELSE TIMESTAMPDIFF(HOUR,ft.fecha_registro,NOW()) >= ".NUMERO_HORAS_EDITAR_MATERIALES_CV." END;";
	    /*
	    if($SubProy != ''){
	        $Query .= " AND sp.subProyectoDesc REGEXP '".str_replace(',','|',$SubProy)."'";
	    }
	    if($eecc != ''){
	        $Query .= " AND ec.empresaColabDesc LIKE '%".$eecc."%'";
	    }
	    if($zonal != ''){
	        $Query .= " AND z.zonalDesc = '".$zonal."'";
	    }
	    if($situacion!= ''){
	        if($situacion  ==  '0'){
	            $Query .= " AND ft.estado_validacion = ''";
	        }else{
	            $Query .= " AND ft.estado_validacion = '".$situacion."'";
	        }
	        
	    }
	    if($mesEjec!=''){
	        $Query .= " HAVING fechaPrevEjec = '".$mesEjec."'";
	    }
	    */
	    $result = $this->db->query($Query,array());
	    //_log($this->db->last_query());
	    return $result;
	}
	
	function updateFichaTecnicaCV($itemplan, $idFichaTecnica, $informacion, $idEstadoPlan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('flg_activo', 1);   //ACTIVO
	        $this->db->where('id_ficha_tecnica', $idFichaTecnica);
	        $this->db->update('ficha_tecnica',  $informacion);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el ficha tecnica.');
	        }else{
                if($idEstadoPlan   ==   ID_ESTADO_PRE_LIQUIDADO && $informacion['estado_validacion']    == FICHA_TECNICA_APROBADA){
                    $dataUpdate = array(
                        "idEstadoPlan" => ID_ESTADO_TERMINADO
                    );
                    $this->db->where('itemPlan', $itemplan);
                    $this->db->update('planobra', $dataUpdate);
                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Hubo un error al actualizar el estadoplan.');
                    }else{
                        $data['error']    = EXIT_SUCCESS;
                        $data['msj']      = 'Se actualizo correctamente!';
                        $this->db->trans_commit();
                    }
                }else{
                    $data['error']    = EXIT_SUCCESS;
                    $data['msj']      = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }	            
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}