<?php
class m_linea_opex_combinatoria extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    
    public function getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2)
    {
        $sql = "SELECT * FROM combinatoria_opex";//estado = 1 : activo
     
        
        $result = $this->db->query($sql,array());
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    function saveConfigOpex($dataInsert,$inputCecoAdd,$inputCuentaAdd,$inputAreaFuncional) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();

            $this->db->where('ceco',$inputCecoAdd);
            $this->db->where('cuenta',$inputCuentaAdd);
            $this->db->where('areafuncional',$inputAreaFuncional);
            $query = $this->db->get('combinatoria_opex');
            if ($query->num_rows() > 0){
                $data['error'] = EXIT_ERROR;
                $data['msj'] = 'Ya se encuentra Registrado';
            }else{
            $this->db->insert('combinatoria_opex', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la configuracion Opex');
            } else {
                $data['idOpex'] = $this->db->insert_id();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro la configuracion Opex';
                $this->db->trans_commit();
            }
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