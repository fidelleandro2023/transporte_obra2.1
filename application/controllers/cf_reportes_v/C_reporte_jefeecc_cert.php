<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_reporte_jefeecc_cert extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_reporte_jefeecc_cert');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){

    	       $data['listaProy'] = $this->m_reporte_jefeecc_cert->getProyectoCert();
               $data['listaEECC'] = $this->m_reporte_jefeecc_cert->getEECCCert();

               $data['listaJefatu'] = $this->m_utils->getJefatura();
               $data['Anio'] = $this->m_utils->getAnioActualAnterior();
               $data['tablaRepJefEECC'] = "";
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_ITEM_PLAN_CERT2);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_reporte_jefeecc_cert',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    
    public function makeHTLMTablaReporteJefaturaEECC($listDatos,$listames,$anio){

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
	                              $a.attr("download","CERTIFICABLE.xls");
	                              $a[0].click();
	                              $a.remove();
	                          }
	                      });
	                      
	            }
	            </script>
                  <table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                       <tr role="row">                           
                            <th style="text-align: center; color:white; font-weight:bold; background-color: #808080;" colspan="2">DESCRIPCION</th>';
         
     

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


            $cabeceraPr.='<th style="text-align: center; color:white; background-color: #808080;" colspan="3">'.$mestexto.'-'.$anio.'</th>';
            $subcabecera.='<th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #00ff00;">VALIDADO </th>
                            <th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #ff0000;">PDTE VALIDAR</th>
                             <th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #808080;">%AVANCE</th>';
        }

        $html.=$cabeceraPr.'<th style="text-align: center; color:white; font-weight:bold; background-color: #808080;" colspan="2">SUMA TOTAL</th>';
        $html.='</tr> <tr role="row">
                        <th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #6565ff;">JEFATURA</th>
                        <th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #6565ff;">EECC</th>'. 
                        $subcabecera.'<th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #6565ff;">CERT</th>
                        <th colspan="1" style="text-align: center; color:white; font-weight:bold; background-color: #6565ff;">NO CERT</th></tr>
                </thead>                    
                <tbody>';

$dato=0;

        foreach($listDatos->result() as $row){       
            $htmlP="";
            $htmlS="";
             $sumaHori_CERT=0;
             $sumaHori_NOCERT=0;

             if($dato==0){
                 $estilo=' style="color:white; background-color: #7cbcb4;"';
                 $dato=1;
             }else{
                $estilo='';
                 $dato=0;
             }


               $htmlP =' <tr '.$estilo.'>
                            <td>'.$row->jefatura.'</td>
                            <td>'.$row->eeccplanobra.'</td>';

							 foreach ($listames as $meses){
                                if(intval($meses)==1){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_1.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_1.'</a></td>
                                              <td>'.round($row->avance_1).' %</td>';

                                     $sumaHori_CERT+=intval($row->CERT_1);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_1);
                                }
                                if(intval($meses)==2){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_2.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_2.'</a></td>
                                              <td>'.round($row->avance_2).' %</td>';

                                    $sumaHori_CERT+=intval($row->CERT_2);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_2);
                                }
                                if(intval($meses)==3){
                                     $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_3.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_3.'</a></td>
                                              <td>'.round($row->avance_3).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_3);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_3);
                                }
                                if(intval($meses)==4){
                                   $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_4.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_4.'</a></td>
                                               <td>'.round($row->avance_4).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_4);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_4);
                                }
                                if(intval($meses)==5){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_5.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_5.'</a></td>
                                              <td>'.round($row->avance_5).' %</td>';

                                    $sumaHori_CERT+=intval($row->CERT_5);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_5);
                                }
                                if(intval($meses)==6){
                                   $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_6.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_6.'</a></td>
                                              <td>'.round($row->avance_6).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_6);
                                    $sumaHori_NOCERT+=intval($row->NOCERT_6);
                                }
                                if(intval($meses)==7){
                                   $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_7.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_7.'</a></td>
                                              <td>'.round($row->avance_7).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_7);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_7);
                                }
                                if(intval($meses)==8){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_8.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_8.'</a></td>
                                              <td>'.round($row->avance_8).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_8);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_8);
                                }
                                if(intval($meses)==9){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_9.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_9.'</a></td>
                                              <td>'.round($row->avance_9).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_9);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_9);
                                }
                                if(intval($meses)==10){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_10.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_10.'</a></td>
                                              <td>'.round($row->avance_10).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_10);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_10);
                                }
                                if(intval($meses)==11){
                                     $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_11.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_11.'</a></td>
                                              <td>'.round($row->avance_11).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_11);
                                     $sumaHori_NOCERT+=intval($row->NOCERT_11);
                                }
                                if(intval($meses)==12){
                                    $htmlS.= '<td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="C"  
                                                    onclick="getDetalle(this)">'.$row->CERT_12.'</a></td>
                                              <td><a style="color:blue" 
                                                    data-anio="'.$anio.'" 
                                                    data-mes="'.$meses.'" 
                                                    data-jef="'.$row->jefatura.'" 
                                                    data-eeccip="'.$row->eeccplanobra.'"
                                                    data-fcert="NC"  
                                                    onclick="getDetalle(this)">'.$row->NOCERT_12.'</a></td>
                                              <td>'.round($row->avance_12).' %</td>';
                                    $sumaHori_CERT+=intval($row->CERT_12);
                                    $sumaHori_NOCERT+=intval($row->NOCERT_12);
                                }
                             }

					 $html .=$htmlP.$htmlS.'<td>'.$sumaHori_CERT.'</td><td>'.$sumaHori_NOCERT.'</td></tr>';

                     $sumaHori_CERT=0;
                     $sumaHori_NOCERT=0;
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
           
           ini_set('max_execution_time',60000);
           ini_set('memory_limit', '3072M');
           
            $proyecto = $this->input->post('proyecto');
            $subProyecto = $this->input->post('subProy');

            $jefatura = $this->input->post('jefatura');
            $eecc = $this->input->post('eecc');

            $mestemp = $this->input->post('mes'); 
            $anio = $this->input->post('anio');

            $columnasSql="";

            if($mestemp==""){
                $mes=array (1,2,3,4,5,6,7,8,9,10,11,12);
            }else{
                $mes="";
                $mes=explode(',',$mestemp);
            }

            if ($anio==''){
                $anio=date('Y');
            }

            foreach ($mes as $datomes){
                 $columnasSql.=", SUM(CASE WHEN (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$datomes."' 
                                    and YEAR(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$anio."' AND flagcert='C')  THEN 1
                                    ELSE 0 END) AS CERT_".$datomes.",
                                  SUM(CASE WHEN (MONTH(DATE(fechaejecucion))='".$datomes."' 
                                   AND YEAR(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$anio."' AND flagcert='NC')  THEN 1
                                    ELSE 0 END) AS NOCERT_".$datomes.", 
                                  (SUM(CASE WHEN (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$datomes."' 
                                    and YEAR(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$anio."' AND flagcert='C')  THEN 1
                                    ELSE 0 END)/(SUM(CASE WHEN (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$datomes."' 
                                    and YEAR(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$anio."' AND flagcert='C')  THEN 1
                                    ELSE 0 END)+SUM(CASE WHEN (MONTH(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$datomes."' 
                                   AND YEAR(str_to_date(fechaejecucion, '%d/%m/%Y'))='".$anio."' AND flagcert='NC')  THEN 1
                                    ELSE 0 END)) )*100  as avance_".$datomes."
                                    ";  
            }
            $DATARESULT=$this->m_reporte_jefeecc_cert->getReporteJefaturaEECC($columnasSql,$proyecto, $subProyecto, $jefatura, $eecc, $mes,$anio);

          $data['tablaRepJefEECC'] = $this->makeHTLMTablaReporteJefaturaEECC($DATARESULT,$mes,$anio);
            $data['error']    = EXIT_SUCCESS;
          
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getHTMLChoiceSubProy(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $Proyecto = $this->input->post('proyecto');
       
            $listaSubProy = $this->m_reporte_jefeecc_cert->getSubProyectoCert($Proyecto);
            $html = '';
            foreach($listaSubProy->result() as $row){
                $html .= '<option value="'.$row->subproyecto.'">'.$row->subproyecto.'</option>';
            }
     
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
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
            $fcert = $this->input->post('fcert');

           $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_reporte_jefeecc_cert->getDetalleIPPTRValNoVal($jefatura,$eecc,$mes,$anio,$fcert));


            $data['error']    = EXIT_SUCCESS;
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function makeHTLMTablaItemplanDet($listaDetalleCert){
        $html = '<table><thead class="thead-default">
                       <tr role="row">                           
                            <th >ITEMPLAN - EECCPTR - PTR</th>
                        </tr>
                        </thead><tbody>


            <table id="detalleCert" class="treetable"><tbody>';

        $iptemplan="";
        $ptr="";
        $empresa="";
        $htmlAux="";

        foreach($listaDetalleCert->result() as $row){  
            $ptr=$row->ptr;
            $empresa=$row->eeccptr;

            if ($iptemplan==""){
                $iptemplan=$row->itemplan;


                $htmlAux='<tr data-tt-id="'.$iptemplan.'" class="branch expanded">
                            <td><span class="folder" ><a href="#"></a></span>'.$iptemplan.'</td></tr>';
                $htmlAux.='<tr data-tt-id="'.$ptr.'" data-tt-parent-id="'.$iptemplan.'"><td>'.$empresa.' -> '.$ptr.'</td></tr>';
            }else{
                if($iptemplan==$row->itemplan){
                    $htmlAux='<tr data-tt-id="'.$ptr.'" data-tt-parent-id="'.$iptemplan.'"><td>'.$empresa.' -> '.$ptr.'</td></tr>';
                }else{
                    $iptemplan=$row->itemplan;
                     $htmlAux='<tr data-tt-id="'.$iptemplan.'" class="branch expanded">
                             <td><span class="folder" ><a href="#"></a></span>'.$iptemplan.'</td></tr>';
                    $htmlAux.='<tr data-tt-id="'.$ptr.'" data-tt-parent-id="'.$iptemplan.'"><td>'.$empresa.' -> '.$ptr.'</td></tr>';
                }
            }

            $html .=$htmlAux;
            $htmlAux="";
        }

                   
         $html .='</tbody></table></tbody></table>

            <script>
            $("#detalleCert").treetable({expandable:true });
                
                </script>';

        return utf8_decode($html);
    }



    

    
}