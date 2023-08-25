<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_reporte_iplan_ptr_primera_aprob extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_reporte_iplan_ptr_primera_aprob');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();        	           
        	  /* $data['listaRegion'] = $this->m_utils->getAllRegion();*/
              $data['Anio'] = date("Y");
               $data['tablaItemPTRFAprob'] = $this->makeHTLMTablaSeguimientoPDO($this->m_reporte_iplan_ptr_primera_aprob->getSeguimientoItemplanPTRFAprob('','','',''),'');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_ITEM_PLAN_PTR_PRIMER_APROB);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_reporte_iplan_ptr_primera_aprob',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    
    public function makeHTLMTablaSeguimientoPDO($listDatos,$datoextra){
    	$listaJson = json_encode($listDatos->result());
        $comiListaJson = "'".$listaJson."'";
        
        $html = '<!--<button class="btn" style="background-color: #28B463; color: white; padding: 10px" onclick="bajar()">Descargar Excel</button>-->
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
                            <th style="text-align: center;" colspan="3">DESCRIPCION</th>
                            <th style="text-align: center;" colspan="3">FECHAS</th>
	                        <th style="TEXT-ALIGN: center;    background-color: #6565ff;" colspan="3">MAT FO</th>
                            <th style="TEXT-ALIGN: center;    background-color: #ffa5009e;" colspan="3">MAT COAX</th>
                                                                                       
                       </tr>
                       <tr role="row">                           
                            <th colspan="1">ITEMPLAN</th>
                            <th colspan="1">PROYECTO</th>
                            <th colspan="1">SUBPROYECTO</th>                            
                            
                            <th colspan="1">PREV EJECUCION</th>                          
                            <th colspan="1">EJECUCION</th>
	                        <th colspan="1">CANCELACION</th>
	                    
                            <th colspan="1">PTR</th>
	                        <th colspan="1">ESTADO</th>
                            <th colspan="1">FECHA PRIMERA APROBACION</th>      
                            
                            
                            <th colspan="1">PTR</th>
                            <th colspan="1">ESTADO</th>
                            <th colspan="1">FECHA PRIMERA APROBACION</th>      
                                        
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){       

               $html .=' <tr>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->proyecto.'</td>
							<td>'.$row->subproyecto.'</td>
                            <td>'.$row->fechaPrevEjec.'</td>
                            <td>'.$row->fechaEjecucion.'</td>
                            <td>'.$row->fechaCancelacion.'</td>
                            <td>'.$row->PTR_MAT_FO.'</td>
                            <td>'.$row->PTR_ESTADO_MAT_FO.'</td>
                            <td>'.$row->PTR_FECAPROB_MAT_FO.'</td>
                            <td>'.$row->PTR_MAT_COAX.'</td>
                            <td>'.$row->PTR_ESTADO_MAT_COAX.'</td>
                            <td>'.$row->PTR_FECAPROB_MAT_COAX.'</td>

						</tr>';
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
            $idProyecto = $this->input->post('proyecto');
            $subProyecto = $this->input->post('subProy');
            $mes = $this->input->post('mes'); 
             $anio = $this->input->post('anio');

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_reporte_iplan_ptr_primera_aprob->getSeguimientoItemplanPTRFAprob($idProyecto,$subProyecto,$mes,$anio),'');
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
            $listaSubProy = $this->m_utils->getAllSubProyectoNoFaseByProyecto($idProyecto);
            $html = '';
            foreach($listaSubProy->result() as $row){
                $html .= '<option value="'.$row->subProyectoDesc.'">'.$row->subProyectoDesc.'</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}