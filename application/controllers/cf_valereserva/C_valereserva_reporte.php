<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_valereserva_reporte extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_valereserva/m_valereserva');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaVR'] = $this->m_valereserva->getAllVR();
               $data['listaMAT'] = $this->m_valereserva->getAllVRIDMAT(); 
               $data['listaDESCMAT'] = $this->m_valereserva->getAllVRDESCMAT();
               $data['listaESTVR'] = $this->m_valereserva->getAllEstadoVR();
               $data['listaSubProyVR'] = $this->m_utils->getAllSubProyecto();

              $data['listaAnio'] = $this->m_valereserva->getAllAnioVR();
              $data['tablaVRWUMATERIAL'] = $this->makeHTLMTablaVRWUMAT($this->m_valereserva->getVR_WU_MATERIAL('','','','','','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_VALERESERVA, ID_PERMISO_HIJO_VALERESERVA_REPORTE);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_reporte_valereserva',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    
    public function makeHTLMTablaVRWUMAT($listDatos){
    	$listaJson = json_encode($listDatos->result());

        $comiListaJson = "'".$listaJson."'";
        
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
                            <th colspan="1">ITEMPLAN</th>
                            <th colspan="1">SUBPROYECTO</th>
                            <th colspan="1">PTR</th>                           
                            <th colspan="1">RESERVA</th>
                            <th colspan="1">COD.MATERIAL</th>
                            <th colspan="1">DESCRIPCION MATERIAL</th>  
                            <th colspan="1">FECHA NEC.</th>                          
                            <th colspan="1">CANT NEC.</th>
	                    <th colspan="1">CANT DIF.</th>
                            <th colspan="1">TOTAL</th>
                            <th colspan="1">TOTAL PARCIAL</th>
                            <th colspan="1">ESTADO VR</th>
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){       
                    $colorestado=$row->colorvr;
                    $estilo=' style="color:white; background-color: '.$colorestado.';"';

               $html .=' <tr>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
                            <td>'.$row->PTRWU.'</td>
                            <td>'.$row->codigo_vr.'</td>
                            <td>'.$row->id_material.'</td>
			    <td>'.$row->descrip_material.'</td>
                            <td>'.$row->fech_nec.'</td>
                            <td style="text-align: right">'.$row->cant_nec.'</td>
                            <td style="text-align: right">'.$row->cant_dif.'</td>
                            <td style="text-align: right">'.$row->total.'</td>
                            <td style="text-align: right">'.$row->totalParcial.'</td>
                            <td'.$estilo.'>'.$row->EstadoVR.'</td>
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
            $codigovr = $this->input->post('codigovr');
            $idmaterial = $this->input->post('idmaterial');
            $descripmat = $this->input->post('descripmat');
            $mes = $this->input->post('mes'); 
            $anio = $this->input->post('anio');
            $estadovr = $this->input->post('estvr');
            $ptr = $this->input->post('ptrvr');
            $subproyecto = $this->input->post('subprovr');

            $data['tablaVRWUMATERIAL'] = $this->makeHTLMTablaVRWUMAT($this->m_valereserva->getVR_WU_MATERIAL($codigovr, $idmaterial, $descripmat, $mes, $anio, $estadovr,$ptr,$subproyecto));
            $data['error']    = EXIT_SUCCESS;
          
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
}