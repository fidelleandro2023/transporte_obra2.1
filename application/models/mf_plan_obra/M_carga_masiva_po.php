<?php
class M_carga_masiva_po extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   loadDataImportPlanObra($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->from('import_planobra');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_planobra");
                if ($this->db->trans_status() === TRUE) {
                    $this->db->query("DELETE FROM import_planobra WHERE nombre_proyecto LIKE '%NOMBRE PROYECTO%'");
                    if ($this->db->trans_status() === TRUE) {
                             /*************************MODIFICACION MIGUEL RIOS 24052018***********************************/
                                 $this->db->query("DELETE FROM import_planobra 
                                                    WHERE trim(nombre_proyecto)=''
                                                        and trim(subproyecto)='' 
                                                        and trim(fecha_inicio)='' 
                                                        and trim(fase)='' 
                                                        and trim(central)='' 
                                                        and trim(empresa_electrica)='';");
                                 if ($this->db->trans_status() === TRUE) {

                                         $this->db->query("UPDATE import_planobra 
                                                            SET estado_plan='Pre Diseño'
                                                            WHERE 1;");

                                          if ($this->db->trans_status() === TRUE){
                                                $this->db->query("UPDATE import_planobra 
                                                            SET fecha_ejecucion=NULL
                                                            WHERE trim(fecha_ejecucion)='' 
                                                            OR fecha_ejecucion='00/00/0000' 
                                                            OR fecha_ejecucion='0000-00-00';");

                                              if ($this->db->trans_status() === TRUE){
                                                    /*************************MIGUEL RIOS 18062018*****************************/
                                                    /******BUSQUEDA Y EXCLUSION DE SUBPROYECTOS ASOCIADOS A  
                                                                    CABLEADO DE EDIFICIOS (CV) y SISEGOS*********/
                                                    $result =  $this->db->query("SELECT COUNT(1) as cantidad
		FROM import_planobra 
		where trim(subproyecto) 
		in (SELECT tab.subProyectoDesc from (
                SELECT
                subProyectoDesc
                FROM
                subproyecto
                WHERE
                idProyecto = 21
    		UNION
                SELECT
                sub2.subProyectoDesc
                FROM
                subproyecto sub2
                WHERE
                sub2.idProyecto = 3 AND NOT sub2.idSubProyecto IN(87, 88, 89)) as tab);");

                                                     $existe =$result->row()->cantidad;   
                                                     if ($existe>0){
                                                         $this->db->query("DELETE FROM import_planobra 
		where trim(subproyecto) 
		in (SELECT tab.subProyectoDesc from (
                SELECT
                subProyectoDesc
                FROM
                subproyecto
                WHERE
                idProyecto = 21
    		UNION
                SELECT
                sub2.subProyectoDesc
                FROM
                subproyecto sub2
                WHERE
                sub2.idProyecto = 3 AND NOT sub2.idSubProyecto IN(87, 88, 89)) as tab);");
                                                         if ($this->db->trans_status() === TRUE) {
                                                                    $resultT =  $this->db->query("SELECT COUNT(1) as cantidad
                                                                                FROM import_planobra");
                                                                    $existeData =$resultT->row()->cantidad;

                                                                    if ($existeData>0){
                                                                        /****CODIGO ORIGINAL***/
                                                                        $this->db->trans_commit();
                                                                        $data['msj'] = 'El modulo de carga masiva no esta autorizado para registrar subproyectos <br>relacionados a CABLEADO DE EDIFICIOS (CV) ni SISEGOS del 2018.<br>Los demas registros estan siendo procesados.';
                                                                        $data ['error']= EXIT_SUCCESS;
                                                                        /*************/
                                                                    }else{
                                                                         $data['msj'] = 'El modulo de carga masiva no esta autorizado para registrar subproyectos <br>relacionados a CABLEADO DE EDIFICIOS (CV) ni SISEGOS del 2018. ';
                                                                    }
                                                           }else{
                                                                $this->db->trans_rollback();
                                                                throw new Exception('ERROR DELETE FROM import_planobra where SUBPROYECTOS relacionado a CABLEADO DE EDIFICIOS (CV) y SISEGOS del 2018',true);
                                                           }
                                                     }else{
                                                                     $this->db->trans_commit();
                                                                    $data ['error']= EXIT_SUCCESS;
                                                                     $data['msj'] = '';
                                                                    /*************/
                                                     }                                 
                                                    /************************************************************************/
                                             }else{
                                                $this->db->trans_rollback();
                                                throw new Exception('ERROR UPDATE FROM import_planobra SET fecha_ejecucion=NULL',true);
                                              }

                                          }else{
                                              $this->db->trans_rollback();
                                                throw new Exception('ERROR UPDATE FROM import_planobra  SET estado_plan=Pre Diseño
                                                            WHERE 1',true);

                                          }
                                        
                                 }else{
                                     $this->db->trans_rollback();
                                 throw new Exception('ERROR DELETE FROM import_planobra where todos sus datos son vacios.',true);
                                 }
                                 /*********************************************************************************************/
               
                            }else{
                                $this->db->trans_rollback();
                                 throw new Exception('ERROR DELETE FROM import_planobra WHERE PTR LIKE %PTR%',true);
                            }           
                }else{
                    $this->db->trans_rollback();
                     throw new Exception('ERROR loadDataImportPlanObra: '.$pathFinal,true);
                }
            } else {
                $this->db->trans_rollback();
                 throw new Exception('ERROR TRUNCATE loadDataImportPlanObra',true);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
	}	
	
	/**************************MIGUEL RIOS 24052018 CORRECCION carga masiva PO************************************/

    function verificaSubproyecto(){
       $Query = " SELECT count(1) as existesub 
                    from import_planobra 
                    where (trim(subproyecto)='' or subproyecto is null);";    
        $result = $this->db->query($Query,array());
        $valor1 =$result->row()->existesub;
        return $valor1 ;
    }
    
    function verificaSubproyectoAntiguos(){
        $sql = " SELECT COUNT(1)count
                   FROM (
                        SELECT DISTINCT i.*
                            FROM import_planobra i, 
                                 subproyecto s
                         WHERE i.subproyecto    = s.subProyectoDesc 
                           AND SUBSTRING_INDEX( s.subproyectoDesc , ' ', 1 ) NOT IN(2016,2017)
                        )t";    
         $result = $this->db->query($sql);  
         return  $result->row()->count;
     }


    function verificaFechaInicio(){
       $Query = " SELECT count(1) as existefech 
                    from import_planobra 
                    where (trim(fecha_inicio)='' or fecha_inicio='00/00/0000' or fecha_inicio='0000-00-00' or fecha_inicio is null);";    
        $result = $this->db->query($Query,array());
        $valor2 =$result->row()->existefech;
        return $valor2 ;
    }


    function verificaCentral(){
       $Query = " SELECT count(1) as existecentral 
                    from import_planobra 
                    where (trim(central)='' or central is null);";    
        $result = $this->db->query($Query,array());
        $valor3 =$result->row()->existecentral;
        return $valor3 ;
    }

    function verificaEEELEC(){
       $Query = " SELECT count(1) as existeEEELEC
                    from import_planobra 
                    where (trim(empresa_electrica)='' or empresa_electrica is null);";    
        $result = $this->db->query($Query,array());
        $valor4 =$result->row()->existeEEELEC;
        return $valor4 ;
    }
    /***********************************************************************************************************/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function   execImportPlaoObraMasivo(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
            $this->db->query("SELECT importPlaoObraMasivo();");
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("SELECT loadLogImportPlanObra('".$this->session->userdata('idPersonaSession')."');");                
                if ($this->db->trans_status() === TRUE) {
                    $this->updateEstadoMasivo();
                    
                    $this->db->trans_commit();
                    $data ['error']= EXIT_SUCCESS;
                }else{
                    $this->db->trans_rollback();
                    throw new Exception('ERROR loadLogPlanObraMasivo IMPORT PLAN OBRA');
                    }                
            }else{
               
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION IMPORT PLAN OBRA');
            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

function   getUploadPoError(){
	    $Query = "  SELECT ipo.*
                	FROM import_planobra ipo where 
                    
                    (SELECT idCentral from central where (UPPER(codigo) = TRIM(UPPER(central)))) is  null 
                    or 
                    (SELECT idProyecto from subproyecto where UPPER(subproyectoDesc) = TRIM(UPPER(subproyecto))) is  null;";
	   
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getUploadPoSuccess(){
	    $Query = "  SELECT *
                	FROM log_import_planobra_su;";
	
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function updateEstadoMasivo() {
        $sql = "UPDATE subproyecto_cambio_estado s, 
                       planobra po 
                   SET po.idEstadoPlan  = s.idEstadoPlan    
                 WHERE po.idSubProyecto = s.idSubProyecto 
                   AND s.flgActivo      = 1
                   AND po.idEstadoPlan  = 1";
        
        $this->db->query($sql,array());
    }
	
}