<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_reporte_cv_jefeecc extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reporte_gerente/m_reporte_cv_jef_eecc');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){

    	      
                $data['Anio'] = $this->m_utils->getAnioConstCV();
                $aniod=date('Y');
                $mesd=$this->getMeses("",$aniod);
               

                $cadenasql=$this->createCadenaSQL($mesd,$aniod);



               $data['tablaRepCVJefEECC'] = $this->makeHTLMTablaRepCVJefaturaEECC($this->m_reporte_cv_jef_eecc->getReporteCVJefaturaEECC($cadenasql),$mesd,$aniod);
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_ITEM_PLAN_CERT2);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reporte_gerente/v_reporte_jefecc_cv',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    
    public function makeHTLMTablaRepCVJefaturaEECC($listDatos,$listames,$anio){
      
    	$listaJson = json_encode($listDatos->result());
        
        $comiListaJson = "'".$listaJson."'";
        
        $cabeceraPr="";
        $subcabecera="";

        $html = '
        
        	<script>

	            function bajar(){
	            
	                console.log("ingreso");
	                var listaJS = '.$comiListaJson.';
	                   
	
	                $.ajax({
	                    async:true,
	                    type:"POST",
	                    dataType:"html",//html
	                    contentType:"application/x-www-form-urlencoded",//application/x-www-form-urlencoded
	                    url:"excelObras",
	                    data:{listaJS  : listaJS                            
	                                    },
	                 
	                    success:function(data){
	                      
	                        var opResult = JSON.parse(data);
	                              var $a=$("<a>");
	                              $a.attr("href",opResult.data);
	                              $("body").append($a);
	                              $a.attr("download","reportecveecc.xls");
	                              $a[0].click();
	                              $a.remove();
	                          }
	                      });
	                      
	            }
	            </script>
                  <table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">';
         
     

        foreach ($listames as $meses){
            $mestexto="";
            switch($meses){
                case 1:  $mestexto="ENE";break;
                case 2:  $mestexto="FEB";break;
                case 3:  $mestexto="MAR";break;
                case 4:  $mestexto="ABR";break;
                case 5:  $mestexto="MAY";break;
                case 6:  $mestexto="JUN";break;
                case 7:  $mestexto="JUL";break;
                case 8:  $mestexto="AGO";break;
                case 9:  $mestexto="SEP";break;
                case 10:  $mestexto="OCT";break;
                case 11:  $mestexto="NOV";break;
                default:  $mestexto="DIC";break;

            }


            $subcabecera.='<th style="text-align: center;">'.$mestexto.'-'.$anio.'</th>';
            
        }

       
        $html.='</tr> <tr role="row">
                        <th colspan="1" style="text-align: center">JEFATURA</th>
                        <th colspan="1" style="text-align: center">EECC</th>
                        <th colspan="1" style="text-align: center">SIN FECHA</th>'. 
                        $subcabecera.'</tr>
                </thead>                    
                <tbody>';

$dato=0;

        foreach($listDatos->result() as $row){       
            $htmlP="";
            $htmlS="";
            

             if($dato==0){
                 $estilo='';
                 $dato=1;
             }else{
                $estilo='';
                 $dato=0;
             }


               $htmlP =' <tr '.$estilo.'>
                            <td>'.$row->jefatura.'</td>
                            <td>'.$row->eeccplanobra.'</td>
                            <td>'.$row->sin_fecha.'</td>';

							 foreach ($listames as $meses){
                                if(intval($meses)==1){
                                    $htmlS.= '<td><a  
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_1.'</a></td>';
                                }
                                if(intval($meses)==2){
                                   $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_2.'</a></td>';

                                }
                                if(intval($meses)==3){
                                    $htmlS.= '<td><a  
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_3.'</a></td>';
                                }
                                if(intval($meses)==4){
                                   $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_4.'</a></td>';
                                }
                                if(intval($meses)==5){
                                    $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_5.'</a></td>';
                                }
                                if(intval($meses)==6){
                                   $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_6.'</a></td>';
                                }
                                if(intval($meses)==7){
                                  $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_7.'</a></td>';
                                }
                                if(intval($meses)==8){
                                    $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_8.'</a></td>';
                                }
                                if(intval($meses)==9){
                                   $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_9.'</a></td>';
                                }
                                if(intval($meses)==10){
                                    $htmlS.= '<td><a 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_10.'</a></td>';
                                }
                                if(intval($meses)==11){
                                     $htmlS.= '<td><a  
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_11.'</a></td>';
                                }
                                if(intval($meses)==12){
                                    $htmlS.= '<td><a
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    onclick="getDetalle(this)">'.$row->FECH_12.'</a></td>';
                                }
                             }
					 $html .=$htmlP.$htmlS.'</tr>';

                 }

			 $html .='</tbody>
                </table>';
                  
        return $html;
    }
    

    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
           ini_set('max_execution_time',80000000);
           ini_set('memory_limit', '3072M');
           
            $mestemp = $this->input->post('mes'); 
            $anio = $this->input->post('anio');

            if ($anio==''){
                $anio=date('Y');
            }


            if($mestemp==""){
                $mesaux=date('m');
                $mes=array();

                if ($anio==date('Y')){
                    for ($i=intval($mesaux);$i<=12;$i++){
                        array_push($mes, $i);
                    }
                }else{
                    for ($i=1;$i<=12;$i++){
                        array_push($mes, $i);
                    }
                }


                
            }else{
                $mes="";
                $mes=explode(',',$mestemp);
            }

            


            $columnasSql=$this->createCadenaSQL($mes,$anio);

            
            $DATARESULT=$this->m_reporte_cv_jef_eecc->getReporteCVJefaturaEECC($columnasSql);

          $data['tablaRepCVJefEECC'] = $this->makeHTLMTablaRepCVJefaturaEECC($DATARESULT,$mes,$anio);
            $data['error']    = EXIT_SUCCESS;
          
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    function createCadenaSQL ($mes,$anio){
           

            $columnasSql="";

            foreach ($mes as $datomes){
                 $columnasSql.=", SUM(CASE WHEN (MONTH(DATE(pcv.fec_termino_constru))='".$datomes."' 
                                    and YEAR(DATE(pcv.fec_termino_constru))='".$anio."')  THEN 1
                                    ELSE 0 END) AS FECH_".$datomes." ";  
            }

            return $columnasSql;
    }

    function getMeses($mestemp,$anio){
       if($mestemp==""){
                $mesaux=date('m');
                $mes=array();

                if ($anio==date('Y')){
                    for ($i=intval($mesaux);$i<=12;$i++){
                        array_push($mes, $i);
                    }
                }else{
                    for ($i=1;$i<=12;$i++){
                        array_push($mes, $i);
                    }
                }


                
            }else{
                $mes="";
                $mes=explode(',',$mestemp);
            }

            return $mes;
    }


   
    function getDetalle(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            $anio = $this->input->post('anio');
            $mes = $this->input->post('mes');
            $jefatura = $this->input->post('jefatura');
            $eecc = $this->input->post('eeccip');         

        $data['tablaDetalleItem'] = $this->makeHTLMTablaCVDet($this->m_reporte_cv_jef_eecc->getDetalleCVFechaT($jefatura,$eecc,$mes,$anio));


            $data['error']    = EXIT_SUCCESS;
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function makeHTLMTablaCVDet($listaDetalleCert){
          $html = '
        <table id="data-table1" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>Nombre Proyecto</th>
                            <th>Jefatura</th>
                            <th>EECC</th>
                            <th>Estado</th>
                            <th>Fecha Creacion itemplan</th>
                            <th>Fecha termino construc</th>
                            <th>% Avance</th>
                        </tr>
                    </thead>
                    <tbody>';
                                                                                               
                foreach($listaDetalleCert->result() as $row){ 
                    
                $html .=' <tr>
                            <td>'.$row->itemplan.'</td> 
                            <td>'.$row->nombreProyecto.'</td> 
                            <td>'.$row->jefatura.'</td> 
                            <td>'.$row->empresaColabDesc.'</td>
                            <td>'.$row->estadoPlanDesc.'</td> 
                            <td>'.$row->fecha_creacion.'</td> 
                            <td>'.$row->fec_termino_constru.'</td> 
                             <td>'.$row->avance.'</td>
                        </tr>';
                 }
             $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }



    

    
}