<?php
class M_registro_itemplan_transporte extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    

	
	
function insertarPlanobraTrans($itemplan,$idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, 
							$fechaInicio,$nombreplan, $indicador,$itemplanPE, $flgTransporte){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();
	    	
	    	$fechainicio=date("Y-m-d H:i:s");

	    	$idProvincia=1;
	    	$idDepartamento=1;
	    	$hasAdelanto='0';
	    		    	
	    	    	 
	    	 $dataInsert = array(
                                    "itemPlan"  => $itemplan,
                                    "nombreProyecto" => strtoupper($nombreplan),
                                    "indicador" => $indicador,
                                    "cantidadTroba" => 0,
                                    "uip" => 0,
                                    "fechaInicio" =>$fechaInicio,
                                    "idEstadoPlan" =>intval($estadoplan),
                                    "idFase" => ID_FASE_ANIO_CREATE_ITEMPLAN,
                                    "idCentralPqt" =>intval($idCentral),
                                    "idEmpresaElec" =>intval($eelec),
                                    "idProvincia" =>intval($idProvincia),
                                    "idDepartamento" =>intval($idDepartamento),
                                    "idSubProyecto" =>intval($idSubproy),
                                    "idZonal" => intval($idzonal),
                                    "idEmpresaColab" =>intval($eecc),
                                    "itemPlanPE"=>$itemplanPE,
                                    "hasAdelanto" =>$hasAdelanto,
                                    "fecha_creacion" =>$fechainicio,
                                    "paquetizado_fg" => 1,
                                    "flg_transporte" => $flgTransporte
                                );

	       $this->db->insert('planobra_transporte', $dataInsert);

	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el plan de obra');
	        }else{
	           	$this->db->trans_commit();

	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se inserto correctamente!';
	        }
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}	
}