<?php
class M_reporte_cv_jef_eecc extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getReporteCVJefaturaEECC($columnasSql){            
        
        $query = "SELECT distinct c.jefatura, 
                  eecc.empresaColabDesc as eeccplanobra ,
                  SUM(CASE WHEN (pcv.fec_termino_constru is null or pcv.fec_termino_constru='') tHEN 1 ELSE 0 END) AS sin_fecha ";
         $query.=$columnasSql;
         $query.="from planobra_detalle_cv pcv,planobra pa, central c, empresacolab eecc
                  where pa.itemplan=pcv.itemplan
                  AND pa.idCentral = c.idCentral
                  and eecc.idEmpresaColab=pa.idEmpresaColab
                  group by c.jefatura, 
                  eecc.empresaColabDesc";
	    $result = $this->db->query($query,array());	   
	    return $result;
         //return 1;
	}	


  function   getReportCVJefEECCOnline(){            
        
    $query = "SELECT distinct c.jefatura, 
              eecc.empresaColabDesc as eeccplanobra,
              SUM(CASE WHEN (pcv.fec_termino_constru is null or pcv.fec_termino_constru='') tHEN 1 ELSE 0 END) AS sin_fecha, 
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='1' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_1,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='2' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_2,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='3' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_3,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='4' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_4,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='5' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_5,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='6' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_6,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='7' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_7,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='8' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_8,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='9' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_9,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='10' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_10,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='11' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_11,
              SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='12' and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())) THEN 1
                                ELSE 0 END) AS FECH_12
            from planobra_detalle_cv pcv,planobra pa, central c, empresacolab eecc
                  where pa.itemplan=pcv.itemplan
                  AND pa.idCentral = c.idCentral
                  and eecc.idEmpresaColab=pa.idEmpresaColab
                  AND pa.idestadoplan in (1,2,3,7,8,11)
                  group by c.jefatura, 
                  eecc.empresaColabDesc";
 
      $result = $this->db->query($query,array());    
      return $result->result();
  }


  function getDetalleCVFechaT($jefatura,$eecc,$mes,$anio){

      $Query = "SELECT  pa.itemplan,
                        pa.nombreProyecto,
                        c.jefatura,
                        estp.estadoPlanDesc,
                        eecc.empresaColabDesc, 
                        pcv.fec_termino_constru,
                        date(pa.fecha_creacion) as fecha_creacion,
                        pcv.avance
                  From  planobra_detalle_cv pcv,planobra pa, central c, empresacolab eecc, estadoplan estp
                  where pa.itemplan=pcv.itemplan
                    AND pa.idCentral = c.idCentral
                    and eecc.idEmpresaColab=pa.idEmpresaColab
                    and pa.idestadoplan=estp.idestadoplan  
                    and c.jefatura='".$jefatura."' 
                and month(pcv.fec_termino_constru)='".$mes."'  
                and year(pcv.fec_termino_constru)='".$anio."'
                and eecc.empresaColabDesc='".$eecc."'" ;
      $result = $this->db->query($Query,array());
      return $result;
  }


  function getDetalleCVJefEECCOnline($jefatura,$eecc,$mes){

      $Query = "SELECT  pa.itemplan,
                        pa.nombreProyecto,
                        c.jefatura,
                        estp.estadoPlanDesc,
                        eecc.empresaColabDesc, 
                        pcv.fec_termino_constru,
                        date(pa.fecha_creacion) as fecha_creacion,
                          pcv.avance
                  From  planobra_detalle_cv pcv,planobra pa, central c, empresacolab eecc, estadoplan estp
                  where pa.itemplan=pcv.itemplan
                    AND pa.idCentral = c.idCentral
                    and eecc.idEmpresaColab=pa.idEmpresaColab
                    and pa.idestadoplan=estp.idestadoplan  
                    AND pa.idestadoplan in (1,2,3,7,8,11)
                    and c.jefatura='".$jefatura."' 
                and MONTH(DATE(pcv.fec_termino_constru))='".$mes."'  
                and YEAR(DATE(pcv.fec_termino_constru))=EXTRACT(YEAR FROM NOW())
                and eecc.empresaColabDesc='".$eecc."'" ;
      $result = $this->db->query($Query,array());
       return $result->result();
  }





}