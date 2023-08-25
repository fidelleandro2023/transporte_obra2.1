<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_seguimiento_pdo extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_seguimiento_pdo');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();
        	   $data['listaZonal'] = $this->m_utils->getAllZonalGroup();        	              
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_pdo->getSeguimientoPDO('','','','','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_SEGUIMIENTO_PDO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_seguimiento_pdo',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    public function getFecIniFinByMes($mes){
        $data['fecInicio'] = '';
        $data['fecFin'] = '';
        
        switch ($mes) {
            case 'ENE':
                $data['fecInicio'] = '2018-01-01';
                $data['fecFin'] = '2018-01-31';
                break;
            case 'FEB':
                $data['fecInicio'] = '2018-02-01';
                $data['fecFin'] = '2018-02-28';
                break;
            case 'MAR':
                $data['fecInicio'] = '2018-03-01';
                $data['fecFin'] = '2018-03-31';
                break;
            case 'ABR':
                $data['fecInicio'] = '2018-04-01';
                $data['fecFin'] = '2018-04-30';
                break;
            case 'MAY':
                $data['fecInicio'] = '2018-05-01';
                $data['fecFin'] = '2018-05-31';
                break;
            case 'JUN':
                $data['fecInicio'] = '2018-06-01';
                $data['fecFin'] = '2018-06-30';
                break;
            case 'JUL':
                $data['fecInicio'] = '2018-07-01';
                $data['fecFin'] = '2018-07-31';
                break;
            case 'AGO':
                $data['fecInicio'] = '2018-08-01';
                $data['fecFin'] = '2018-08-31';
                break;
            case 'SEP':
                $data['fecInicio'] = '2018-09-01';
                $data['fecFin'] = '2018-09-30';
                break;
            case 'OCT':
                $data['fecInicio'] = '2018-10-01';
                $data['fecFin'] = '2018-10-31';
                break;
            case 'NOV':
                $data['fecInicio'] = '2018-11-01';
                $data['fecFin'] = '2018-11-30';
                break;
            case 'DIC':
                $data['fecInicio'] = '2018-12-01';
                $data['fecFin'] = '2018-12-31';
                break;
        }
    }
    public function makeHTLMTablaSeguimientoPDO($listDatos){
    	$listaJson = json_encode($listDatos->result());

        $comiListaJson = "'".$listaJson."'";
        
        $html = '<button class="btn" style="background-color: #28B463; color: white; padding: 10px" onclick="bajar()">Descargar Excel</button>
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
	                    //beforeSend: function(){},
	                    success:function(data){
	                        //alert(data);
	                        var opResult = JSON.parse(data);
	                              var $a=$("<a>");
	                              $a.attr("href",opResult.data);
	                              //$a.html("LNK");
	                              $("body").append($a);
	                              $a.attr("download","SeguimientoPO.xls");
	                              $a[0].click();
	                              $a.remove();
	                          }
	                      });
	
	            console.log("se envio a ruta");
	            }
	            </script>
                  <table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                       <tr role="row">                           
                            <th style="text-align: center;" colspan="4">DESCRIPCION</th>
                            <th style="text-align: center;;" colspan="2">OBRAS</th>
                            <th style="TEXT-ALIGN: center;" colspan="5">MAT_COAX</th>
                            <th style="TEXT-ALIGN: center;" colspan="5">MAT_FO</th>                            
                            <th style="TEXT-ALIGN: center;" colspan="5">MAT_FUENTE</th>
                            <th style="TEXT-ALIGN: center;" colspan="5">MAT_FO_OC</th>
                            <th style="TEXT-ALIGN: center;" colspan="5">MAT_COAX_OC</th>
                            <th style="TEXT-ALIGN: center;" colspan="5">MAT_ENER</th>                                                    
                       </tr>
                       <tr role="row">                           
                            <th colspan="1">SUB PROYECTO</th>
                            <th colspan="1">ZONAL</th>
                            <th colspan="1">EECC</th>
                            <th colspan="1">TOTAL OBRAS</th>
                            <th colspan="1">EN OBRA</th>
                            <th colspan="1">TERMINADO</th>                            
                            
                            
                            <th colspan="1">CREADO MAT_COAX</th>
                            <th colspan="1">VR APROB MAT_COAX</th>
                            <th colspan="1">% VR MAT_COAX</th>      
                            <th colspan="1">VR ADIC MAT_COAX</th>
                            <th colspan="1">TPO. APROB MAT_COAX</th>
                            
                            <th colspan="1">CREADO MAT_FO</th>
                            <th colspan="1">VR APROB MAT_FO</th>
                            <th colspan="1">% VR MAT_FO</th>      
                            <th colspan="1">VR ADIC MAT_FO</th>
                            <th colspan="1">TPO. APROB MAT_FO</th>
                            
                            <th colspan="1">CREADO MAT_FUENTE</th>
                            <th colspan="1">VR APROB MAT_FUENTE</th>
                            <th colspan="1">% VR MAT_FUENTE</th>      
                            <th colspan="1">VR ADIC MAT_FUENTE</th>
                            <th colspan="1">TPO. APROB MAT_FUENTE</th>
                            
                            <th colspan="1">CREADO MAT_FO_OC</th>
                            <th colspan="1">VR APROB MAT_FO_OC</th>
                            <th colspan="1">% VR MAT_FO_OC</th>      
                            <th colspan="1">VR ADIC MAT_FO_OC</th>
                            <th colspan="1">TPO. APROB MAT_FO_OC</th>
                            
                            <th colspan="1">CREADO MAT_COAX_OC</th>
                            <th colspan="1">VR APROB MAT_COAX_OC</th>
                            <th colspan="1">% VR MAT_COAX_OC</th>      
                            <th colspan="1">VR ADIC MAT_COAX_OC</th>
                            <th colspan="1">TPO. APROB MAT_COAX_OC</th>
                            
                            <th colspan="1">CREADO MAT_ENER</th>
                            <th colspan="1">VR APROB MAT_ENER</th>
                            <th colspan="1">% VR MAT_ENER</th>      
                            <th colspan="1">VR ADIC MAT_ENER</th>
                            <th colspan="1">TPO. APROB MAT_ENER</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){   
                $newtotal =  $row->total_obras - $row->cancelado;             
                    
                $html .=' <tr>
                            <td>'.$row->subProyectoDesc.'</td>
                            <td>'.$row->zonal.'</td>
                            <td>'.$row->eecc.'</td>
							<td>'.$newtotal.'</td>
							<td>'.$row->en_obra.'</td>		
							<td>'.$row->terminado.'</td>
								
							<td>'.$row->mat_coax_crea.'</td>
							<td>'.$row->mat_coax_apro.'</td>
							<td>'.$this->getPorcentaje($newtotal,$row->mat_coax_apro).'%</td>
							<td>'.(($row->mat_coax_vradic!=null) ? $row->mat_coax_vradic : '0').'</td>
                                                        <td>'.(($row->mat_coax_tpo_apro!=null) ? $this->before(':',  $row->mat_coax_tpo_apro ) : '0').'</td>
                            <td>'.$row->mat_fo_crea.'</td>
							<td>'.$row->mat_fo_apro.'</td>
							<td>'.$this->getPorcentaje($newtotal,$row->mat_fo_apro).'%</td>
							<td>'.(($row->mat_fo_vradic!=null) ? $row->mat_fo_vradic : '0').'</td>
                            <td>'.(($row->mat_fo_tpo_apro!=null) ? $this->before(':',  $row->mat_fo_tpo_apro) : '0').'</td>
							<td>'.$row->mat_fuente_crea.'</td>
							<td>'.$row->mat_fuente_apro.'</td>
							<td>'.$this->getPorcentaje($newtotal,$row->mat_fuente_apro).'%</td>
							<td>'.(($row->mat_fuente_vradic!=null) ? $row->mat_fuente_vradic : '0').'</td>
                            <td>'.(($row->mat_fuente_tpo_apro!=null) ? $this->before(':',  $row->mat_fuente_tpo_apro) : '0').'</td>
                            <td>'.$row->mat_fo_oc_crea.'</td>
							<td>'.$row->mat_fo_oc_apro.'</td>
							<td>'.$this->getPorcentaje($newtotal,$row->mat_fo_oc_apro).'%</td>
							<td>'.(($row->mat_fo_oc_vradic!=null) ? $row->mat_fo_oc_vradic : '0').'</td>
                            <td>'.(($row->mat_fo_oc_tpo_apro!=null) ? $this->before(':',  $row->mat_fo_oc_tpo_apro) : '0').'</td>
							<td>'.$row->mat_coax_oc_crea.'</td>
							<td>'.$row->mat_coax_oc_apro.'</td>
							<td>'.$this->getPorcentaje($newtotal,$row->mat_coax_oc_apro).'%</td>
							<td>'.(($row->mat_coax_oc_vradic!=null) ? $row->mat_coax_oc_vradic : '0').'</td>
                            <td>'.(($row->mat_coax_oc_tpo_apro!=null) ? $this->before(':',  $row->mat_coax_oc_tpo_apro) : '0').'</td>	
							<td>'.$row->mat_ener_crea.'</td>
							<td>'.$row->mat_ener_apro.'</td>
							<td>'.$this->getPorcentaje($newtotal,$row->mat_ener_apro).'%</td>
							<td>'.(($row->mat_ener_vradic!=null) ? $row->mat_ener_vradic : '0').'</td>
                            <td>'.(($row->mat_ener_tpo_apro!=null) ? $this->before(':',  $row->mat_ener_tpo_apro) : '0').'</td>		
							        
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
public function before ($val, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $val));
    }

  public function getPorcentaje($max, $min){
        if($max!=0){
            return round(($min*100)/$max, 0);
        }else{
            return '0%';
        }
        
    }

    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProyecto = $this->input->post('proyecto');
            $idSubProyecto = $this->input->post('subProy');
            $zonal = $this->input->post('zonal');         
            $mesEjec = $this->input->post('mes');
            $hasFiltroFec = "0";
            $fechaInicio = '';
            $fechaFin = '';
            if($mesEjec!=''){
                $hasFiltroFec = "1";
                $fechas = $this->getFecIniFinByMes($mesEjec);
                $fechaInicio = $fechas['fecInicio'];
                $fechaFin = $fechas['fecFin'];
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_pdo->getSeguimientoPDO($mesEjec,$hasFiltroFec,$fechaInicio,$fechaFin,$idProyecto,$idSubProyecto,$zonal));
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
            $idProyecto = $this->input->post('proyecto');
            $listaSubProy = $this->m_utils->getAllSubProyectoByProyecto($idProyecto);
            $html = '';
            foreach($listaSubProy->result() as $row){
                $html .= '<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}