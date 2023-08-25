<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_seguimiento_ec extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_seguimiento_ec');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();
        	   $data['listaZonal'] = $this->m_utils->getAllZonalGroup();        	              
               //$data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_ec->getSeguimientoPDO('','','','','','',''));
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_ec->getSeguimientoPDO('','','','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_SEGUIMIENTO_PDO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_seguimiento_ec',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    public function getFecIniFinByMes($mesEjec, $ano){
        $data['fecInicio'] = '';
        $data['fecFin'] = '';

        $array1pl = array();
        $array2pa = array();

        $meses = explode(",", $mesEjec);

        foreach ($meses as $mes) {
            
            switch ($mes) {
            case 'ENE':
                $data['fecInicio'] = $ano.'-01-01';
                $data['fecFin'] = $ano.'-01-31';
                break;
            case 'FEB':
                $data['fecInicio'] = $ano.'-02-01';
                $data['fecFin'] = $ano.'-02-28';
                break;
            case 'MAR':
                $data['fecInicio'] = $ano.'-03-01';
                $data['fecFin'] = $ano.'-03-31';
                break;
            case 'ABR':
                $data['fecInicio'] = $ano.'-04-01';
                $data['fecFin'] = $ano.'-04-30';
                break;
            case 'MAY':
                $data['fecInicio'] = $ano.'-05-01';
                $data['fecFin'] = $ano.'-05-31';
                break;
            case 'JUN':
                $data['fecInicio'] = $ano.'-06-01';
                $data['fecFin'] = $ano.'-06-30';
                break;
            case 'JUL':
                $data['fecInicio'] = $ano.'-07-01';
                $data['fecFin'] = $ano.'-07-31';
                break;
            case 'AGO':
                $data['fecInicio'] = $ano.'-08-01';
                $data['fecFin'] = $ano.'-08-31';
                break;
            case 'SEP':
                $data['fecInicio'] = $ano.'-09-01';
                $data['fecFin'] = $ano.'-09-30';
                break;
            case 'OCT':
                $data['fecInicio'] = $ano.'-10-01';
                $data['fecFin'] = $ano.'-10-31';
                break;
            case 'NOV':
                $data['fecInicio'] = $ano.'-11-01';
                $data['fecFin'] = $ano.'-11-30';
                break;
            case 'DIC':
                $data['fecInicio'] = $ano.'-12-01';
                $data['fecFin'] = $ano.'-12-31';
                break;

            }

            array_push($array1pl, "pb.fechaPrevEjec BETWEEN '".$data['fecInicio']."' AND '".$data['fecFin']."'
OR ");
            array_push($array2pa, "pa.fechaPrevEjec BETWEEN '".$data['fecInicio']."' AND '".$data['fecFin']."'
OR ");
        }

        $data['array1pl'] = $array1pl;
        $data['array2pa'] = $array2pa;
        
        return $data;
    }
    public function makeHTLMTablaSeguimientoPDO($listDatos){
    
    	//$listaJson = json_encode($listDatos->result());

        //$comiListaJson = "'".$listaJson."'"; // ...x -> var listaJS = '.$comiListaJson.';
        
        $html = '<button class="btn" style="background-color: #28B463; color: white; padding: 10px" onclick="bajar()">Descargar Detalle</button>
        	<script>
            
	            function bajar(){
	                console.log("ingreso");
	                // ...x
	                   
	
	                $.ajax({
	                    async:true,
	                    type:"POST",
	                    dataType:"html",//html
	                    contentType:"application/x-www-form-urlencoded",//application/x-www-form-urlencoded
	                    url:"excelDetalleEC",
	                    
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
	
	            }
	            </script>
                  <table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="2"></th>
                            <th style="text-align:center" colspan="4">TOTAL OBRAS</th>
                            <th style="text-align:center" colspan="8">MAT_COAX</th>
                            <th style="text-align:center" colspan="8">MAT_FO</th>
                            <th style="text-align:center" colspan="8">MAT_FUENTE</th>
                            <th style="text-align:center" colspan="8">MAT_FO_OC</th>
                            <th style="text-align:center" colspan="8">MAT_COAX_OC</th>
                            <th style="text-align:center" colspan="8">MAT_ENER</th>
                        </tr>
                       
                       <tr role="row">                           
                            <th colspan="1">PROYECTO</th>
                            <th colspan="1">SUB PROYECTO</th>
                            <th colspan="1">COBRA</th>
                            <th colspan="1">LARI</th>
                            <th colspan="1">DOMINION</th>
                            <th colspan="1">EZENTIS</th>                            
                            
                            <th colspan="1">CREADO MAT_COAX COBRA</th>
                            <th colspan="1">VR APROB MAT_COAX COBRA</th>
                            <th colspan="1">CREADO MAT_COAX LARI</th>
                            <th colspan="1">VR APROB MAT_COAX LARI</th>
                            <th colspan="1">CREADO MAT_COAX DOMINION</th>
                            <th colspan="1">VR APROB MAT_COAX DOMINION</th>
                            <th colspan="1">CREADO MAT_COAX EZENTIS</th>
                            <th colspan="1">VR APROB MAT_COAX EZENTIS</th>

                            <th colspan="1">CREADO MAT_FO COBRA</th>
                            <th colspan="1">VR APROB MAT_FO COBRA</th>
                            <th colspan="1">CREADO MAT_FO LARI</th>
                            <th colspan="1">VR APROB MAT_FO LARI</th>
                            <th colspan="1">CREADO MAT_FO DOMINION</th>
                            <th colspan="1">VR APROB MAT_FO DOMINION</th>
                            <th colspan="1">CREADO MAT_FO EZENTIS</th>
                            <th colspan="1">VR APROB MAT_FO EZENTIS</th>

                            <th colspan="1">CREADO MAT_FUENTE COBRA</th>
                            <th colspan="1">VR APROB MAT_FUENTE COBRA</th>
                            <th colspan="1">CREADO MAT_FUENTE LARI</th>
                            <th colspan="1">VR APROB MAT_FUENTE LARI</th>
                            <th colspan="1">CREADO MAT_FUENTE DOMINION</th>
                            <th colspan="1">VR APROB MAT_FUENTE DOMINION</th>
                            <th colspan="1">CREADO MAT_FUENTE EZENTIS</th>
                            <th colspan="1">VR APROB MAT_FUENTE EZENTIS</th>

                            <th colspan="1">CREADO MAT_FO_OC COBRA</th>
                            <th colspan="1">VR APROB MAT_FO_OC COBRA</th>
                            <th colspan="1">CREADO MAT_FO_OC LARI</th>
                            <th colspan="1">VR APROB MAT_FO_OC LARI</th>
                            <th colspan="1">CREADO MAT_FO_OC DOMINION</th>
                            <th colspan="1">VR APROB MAT_FO_OC DOMINION</th>
                            <th colspan="1">CREADO MAT_FO_OC EZENTIS</th>
                            <th colspan="1">VR APROB MAT_FO_OC EZENTIS</th>

                            <th colspan="1">CREADO MAT_COAX_OC COBRA</th>
                            <th colspan="1">VR APROB MAT_COAX_OC COBRA</th>
                            <th colspan="1">CREADO MAT_COAX_OC LARI</th>
                            <th colspan="1">VR APROB MAT_COAX_OC LARI</th>
                            <th colspan="1">CREADO MAT_COAX_OC DOMINION</th>
                            <th colspan="1">VR APROB MAT_COAX_OC DOMINION</th>
                            <th colspan="1">CREADO MAT_COAX_OC EZENTIS</th>
                            <th colspan="1">VR APROB MAT_COAX_OC EZENTIS</th>

                            <th colspan="1">CREADO MAT_ENER COBRA</th>
                            <th colspan="1">VR APROB MAT_ENER COBRA</th>
                            <th colspan="1">CREADO MAT_ENER LARI</th>
                            <th colspan="1">VR APROB MAT_ENER LARI</th>
                            <th colspan="1">CREADO MAT_ENER DOMINION</th>
                            <th colspan="1">VR APROB MAT_ENER DOMINION</th>
                            <th colspan="1">CREADO MAT_ENER EZENTIS</th>
                            <th colspan="1">VR APROB MAT_ENER EZENTIS</th>


                        </tr>
                       

                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){   
                //$newtotal =  $row->total_obras - $row->cancelado;             
                    
                $html .=' <tr>
                            <td>'.$row->proyectoDesc.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
                            <td>'.$row->totalCOBRA.'</td>
							<td>'.$row->totalLARI.'</td>
							<td>'.$row->totalDOMINION.'</td>		
							<td>'.$row->totalEZENTIS.'</td>
								
							<td>'.$row->creadosMatCoaxCOBRA.'</td>
							<td>'.$row->aproMatCoaxCOBRA.'</td>
							<td>'.$row->creadosMatCoaxLARI.'</td>
							<td>'.$row->aproMatCoaxLARI.'</td>
                            <td>'.$row->creadosMatCoaxDOMINION.'</td>
                            <td>'.$row->aproMatCoaxDOMINION.'</td>
							<td>'.$row->creadosMatCoaxEZENTIS.'</td>
							<td>'.$row->aproMatCoaxEZENTIS.'</td>
                            <td>'.$row->creadosMatFoCOBRA.'</td>
                            <td>'.$row->aproMatFoCOBRA.'</td>
                            <td>'.$row->creadosMatFoLARI.'</td>
                            <td>'.$row->aproMatFoLARI.'</td>
                            <td>'.$row->creadosMatFoDOMINION.'</td>
                            <td>'.$row->aproMatFoDOMINION.'</td>
                            <td>'.$row->creadosMatFoEZENTIS.'</td>
                            <td>'.$row->aproMatFoEZENTIS.'</td>
                            <td>'.$row->creadosMatFuenteCOBRA.'</td>
							<td>'.$row->aproMatFuenteCOBRA.'</td>
                            <td>'.$row->creadosMatFuenteLARI.'</td>
                            <td>'.$row->aproMatFuenteLARI.'</td>
                            <td>'.$row->creadosMatFuenteDOMINION.'</td>
                            <td>'.$row->aproMatFuenteDOMINION.'</td>
                            <td>'.$row->creadosMatFuenteEZENTIS.'</td>
                            <td>'.$row->aproMatFuenteEZENTIS.'</td>

                            <td>'.$row->creadosMatFoOcCOBRA.'</td>
                            <td>'.$row->aproMatFoOcCOBRA.'</td>
                            <td>'.$row->creadosMatFoOcLARI.'</td>
                            <td>'.$row->aproMatFoOcLARI.'</td>
                            <td>'.$row->creadosMatFoOcDOMINION.'</td>
                            <td>'.$row->aproMatFoOcDOMINION.'</td>
                            <td>'.$row->creadosMatFoOcEZENTIS.'</td>
                            <td>'.$row->aproMatFoOcEZENTIS.'</td>

                            <td>'.$row->creadosMatCoaxOcCOBRA.'</td>
                            <td>'.$row->aproMatCoaxOcCOBRA.'</td>
                            <td>'.$row->creadosMatCoaxOcLARI.'</td>
                            <td>'.$row->aproMatCoaxOcLARI.'</td>
                            <td>'.$row->creadosMatCoaxOcDOMINION.'</td>
                            <td>'.$row->aproMatCoaxOcDOMINION.'</td>
                            <td>'.$row->creadosMatCoaxOcEZENTIS.'</td>
                            <td>'.$row->aproMatCoaxOcEZENTIS.'</td>

                            <td>'.$row->creadosMatEnerCOBRA.'</td>
                            <td>'.$row->aproMatEnerCOBRA.'</td>
                            <td>'.$row->creadosMatEnerLARI.'</td>
                            <td>'.$row->aproMatEnerLARI.'</td>
                            <td>'.$row->creadosMatEnerDOMINION.'</td>
                            <td>'.$row->aproMatEnerDOMINION.'</td>
                            <td>'.$row->creadosMatEnerEZENTIS.'</td>
                            <td>'.$row->aproMatEnerEZENTIS.'</td>

							        
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
            $mesEjec = $this->input->post('mes');
            $ano = $this->input->post('ano'); 
            //$hasFiltroFec = "0";
            $fechaInicio = '';
            $fechaFin = '';

            $cadenaPl = '';
            $cadenaPa = '';
            if($mesEjec==''){
                $mesEjec = 'ENE,FEB,MAR,ABR,MAY,JUN,JUL,AGO,SEP,OCT,NOV,DIC';
            }
            if($ano!=''){
                //$hasFiltroFec = "1";
                $arrays = $this->getFecIniFinByMes($mesEjec,$ano);
                $array1pl = $arrays['array1pl'];
                $array2pa = $arrays['array2pa'];

                foreach($array1pl as $row){
                    $cadenaPl .= $row;
                }
                foreach($array2pa as $row){
                    $cadenaPa .= $row;
                }
                $cadenaPl = '('.substr($cadenaPl, 0, -3).')';
                $cadenaPa = '('.substr($cadenaPa, 0, -3).')';
            }
            
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_ec->getSeguimientoPDO($mesEjec,'',$cadenaPl,$cadenaPa,$idProyecto,$idSubProyecto));
            //$data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_ec->getSeguimientoPDO('','','','','',''));
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

    public function creaFiltroMes($mesEjec, $ano){
        _log('-->EN CREAFILTROMES MESES SON: '.$mesEjec.' y AÑO ES: '.$ano);
        
        // $cadenaMes = "('";

        // if(isset($mesEjec)){
        //     $meses = explode(",", $mesEjec);
        // }else{
        //     $cadenaMes = '';
        // }
        // foreach ($meses as $mes) {
        //     $cadenaMes .= $mes."',";
        // }
        // $cadenaMes = substr($cadenaMes, 0, -1).')';



    }

}