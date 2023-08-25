<?php
class M_reporte_jefeecc_cert extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getReporteJefaturaEECC($columnasSql,$proyecto, $subProyecto, $jefatura, $eecc, $mes,$anio){            
        
        $query = "SELECT DISTINCT jefatura,
        eeccplanobra ";
         $query.=$columnasSql;
         $query.="from import_report_certificacion
              WHERE not fechaejecucion is null ";

        if ($proyecto!=''){
           $query.=" AND proyecto='".$proyecto."' ";
        }

        if ($subProyecto!=''){
           $query.=" AND subproyecto='".$subProyecto."' ";
        }

        if ($jefatura!=''){
           $query.=" AND jefatura='".$jefatura."' ";
        }

         if ($eecc!=''){
           $query.=" and eeccplanobra='".$eecc."' ";
        }

        if($anio!=''){
            if($mes!=''){
                $query.=" and (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y')) IN  (".implode(',', $mes).") and year(str_to_date(fechaejecucion, '%d/%m/%Y'))=". date("Y").") ";
            }else{
                $query.=" and (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y')) IN  (".date("m").") and year(str_to_date(fechaejecucion, '%d/%m/%Y'))=". date("Y").") ";
            }
        }else{
            if($mes!=''){
                $query.=" and (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y')) IN  (".implode(',', $mes).") and year(str_to_date(fechaejecucion, '%d/%m/%Y'))=". $anio.") ";
            }else{
                 $query.=" and (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y')) IN  (".date("m").") and year(str_to_date(fechaejecucion, '%d/%m/%Y'))=". $anio.") ";
            }
        }

        $query.="GROUP BY jefatura, eeccplanobra ORDER BY jefatura, eeccplanobra";
 
	    $result = $this->db->query($query,array());	   
	    return $result;
	}	

  function getProyectoCert(){
    
       $Query = "SELECT proyecto 
                     from import_report_certificacion
                  group by proyecto" ;
      $result = $this->db->query($Query,array());
      return $result;
  }

 function getSubProyectoCert($proyecto){
    
       $Query = "SELECT subproyecto 
                     from import_report_certificacion
                     where proyecto='".$proyecto."' 
                  group by subproyecto" ;
      $result = $this->db->query($Query,array());
      return $result;
  }

  function getEECCCert(){
    
       $Query = "SELECT eeccplanobra 
                     from import_report_certificacion
                  group by eeccplanobra" ;
      $result = $this->db->query($Query,array());
      return $result;
  }


  function getDetalleIPPTRValNoVal($jefatura,$eecc,$mes,$anio,$flag){

      $Query = "SELECT itemplan,eeccptr, ptr 
                  from import_report_certificacion 
                where jefatura='".$jefatura."'  
                and eeccplanobra='".$eecc."' 
                and month(date(fechaejecucion))='".$mes."'  
                and year(date(fechaejecucion))='".$anio."'
                and flagcert='".$flag."'" ;
      $result = $this->db->query($Query,array());
      return $result;



  }





}