<?php
class M_linea_opex_fase extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    
    public function getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2)
    {
        $sql = "SELECT DISTINCT * FROM (SELECT fa.faseDesc,li.idlineaopex,li.presupuesto,li.disponible_proyectado,li.monto_real,li.idlineaopex_fase FROM lineaopex_fase as li INNER JOIN fase as fa ON li.idfase = fa.idFase) as que INNER JOIN lineaopex as fa ON que.idlineaopex = fa.idlineaopex";//estado = 1 : activo
     
        
        $result = $this->db->query($sql,array());
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getFases() {
        $sql = "SELECT * FROM fase";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getLineas() {
        $sql = "SELECT * FROM lineaopex";

        $result = $this->db->query($sql);
        return $result->result();
    }

    function saveConfigOpexLinea($dataInsert,$inputLinea,$inputFase) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->where('idlineaopex',$inputLinea);
            $this->db->where('idfase',$inputFase);
            
            $query = $this->db->get('lineaopex_fase');
            if ($query->num_rows() > 0){
                $data['error'] = EXIT_ERROR;
                $data['msj'] = 'Ya se encuentra asociada la Linea Opex a la Fase seleccionada';
            }else{
           
            $this->db->insert('lineaopex_fase', $dataInsert);
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



    function saveConfigOpexLineaActuaizar($dataInsert,$inputLinea) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
           
          
            $this->db->where('idlineaopex_fase',$inputLinea);
            $this->db->update('lineaopex_fase', $dataInsert);
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se registro la configuracion Opex';
            $this->db->trans_commit();
           
            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getlineaxfase() {
        $sql = "SELECT DISTINCT * FROM (SELECT fa.faseDesc,li.idlineaopex,li.`idlineaopex_fase`,li.presupuesto,li.disponible_proyectado,li.monto_real  FROM lineaopex_fase as li INNER JOIN fase as fa ON li.idfase = fa.idFase) as que INNER JOIN lineaopex as fa ON que.idlineaopex = fa.idlineaopex";

        $result = $this->db->query($sql);
        return $result->result();
    }

    


    function saveConfigOpexLineaPresupuesto($inputLinearecepcion,$inputLineaenvio,$arrayDataEnvio,$arrayDataRecepcion) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
       
            $this->db->where('idlineaopex_fase',$inputLineaenvio);
            
            $query = $this->db->get('lineaopex_fase');
            if ($query->num_rows() == 1){

                $this->db->where('idlineaopex_fase',$inputLineaenvio);
                $this->db->update('lineaopex_fase', $arrayDataEnvio);

                $this->db->where('idlineaopex_fase',$inputLinearecepcion);
                $this->db->update('lineaopex_fase', $arrayDataRecepcion);



                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro la configuracion Opex';
                $this->db->trans_commit();
               
            }else{

                $data['error'] = EXIT_ERROR;
                $data['msj'] = 'No se puede modificar el presupuesto';
           
            }
            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    public function insertPepSubProyectoBolsa($arrayInsert,$pep1,$idFase){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();            
            $this->db->insert_batch('bolsa_pep', $arrayInsert);
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_planobra_po');
            }else{

                $iter=$this->verificaionMargen($pep1);
                if($iter==0){
                $dataInsert = array("pep1" => $pep1,"fase"  => $idFase);
                $this->db->insert('pep1_margen', $dataInsert);
                }
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }            
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }



    public function insertPep2correlativo($pep1,$iterador){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;


        
        

        $iter=$this->pep2Verificacion($pep1);
        $dataInsert = array(
             "pep1" => $pep1,
            "correlativo"  => $iter
        );
        try{
            $this->db->trans_begin();      
            
            if($iter==0){
            $this->db->insert('pep1xcorrelativo', $dataInsert);
            }else{
               /*
                $arrayUpdate = array(
                  
                   "correlativo"  => $iter
               );
                $this->db->where('pep1', $pep1);
                $this->db->update('pep1xcorrelativo', $arrayUpdate);
                */

            }
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_planobra_po');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }            
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function pep2Verificacion($pep1){

        $this->db->where('pep1',$pep1);
        
        $result = $this->db->get('pep1xcorrelativo')->num_rows();
        
        //return $result+1;
        return $result;
       
    }


    public function verificaionMargen($pep1){

        $this->db->where('pep1',$pep1);
        
        $result = $this->db->get('pep1_margen')->num_rows();
        
        //return $result+1;
        return $result;
       
    }
    
    public function addLinea($dataInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
    
        try {
            $this->db->trans_begin();
            $this->db->insert('lineaopex', $dataInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al crear linea opex');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se creo correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }

    public function tipoMargen($idPepBolsa, $arrayUpdate,$pep1,$tipo)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
    
        try {
            $this->db->trans_begin();
            $this->db->where('idpepmar', $idPepBolsa);
            $this->db->update('pep1_margen', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar margen_bolsa_pep!!');
            } else {
                if($tipo="CORRELATIVO"){
                $this->m_bolsa_margen_pep->insertPep2correlativo($pep1,"0");
                }
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }
    
}