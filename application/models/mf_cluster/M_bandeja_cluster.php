<?php
class M_bandeja_cluster extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getItemplanPreRegistro($idSubPro, $idZonal, $idEecc, $idSituacion){
        $Query = ' SELECT pc.*,sp.subProyectoDesc, p.proyectoDesc,
                    z.zonalDesc, ec.empresaColabDesc, c.jefatura
                     FROM planobra_cluster pc, subproyecto sp, proyecto p, central c, empresacolab ec, zonal z
                    where pc.idSubProyecto = sp.idSubProyecto
                    and sp.idProyecto = p.idProyecto
                    and pc.idCentral = c.idCentral
                    and c.idZonal = z.idZonal
                    and c.idEmpresaColab = ec.idEmpresaColab
                    and pc.estado in (0,1) 
                    and flg_tipo = 1'; 
        if($idSubPro!=null){
            $Query .= ' AND sp.idSubProyecto = '.$idSubPro; 
        }
        if($idZonal!=null){
            $Query .= ' AND z.idZonal IN ('.$idZonal.')';
        }
        if($idEecc!=null){
            $Query .= ' AND ec.idEmpresaColab = '.$idEecc;
        }
        if($idSituacion!=null){
            $Query .= ' AND pc.estado = '.$idSituacion;
        }
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}
	
	function   getHijosClusterByItemplan($codCluster){
	    $Query = ' SELECT * FROM planobra_cluster_hijos where codigo_cluster = ?';
	    $result = $this->db->query($Query,array($codCluster));
	    return $result;
	}
	
function updateClusterPadre($codigo_cluster, $dataCluster){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_cluster', $codigo_cluster);
			$this->db->update('planobra_cluster', $dataCluster);			
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el updateClusterPadre');
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
    
    function   getEstadoClusterByCod($codigo){
        $Query = "SELECT estado FROM  planobra_cluster WHERE codigo_cluster  = ? LIMIT 1";
        $result = $this->db->query($Query,array($codigo));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
	
}