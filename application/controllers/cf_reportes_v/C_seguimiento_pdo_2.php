<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_seguimiento_pdo_2 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_seguimiento_pdo_2');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();        	           
        	   $data['listaRegion'] = $this->m_utils->getAllRegion();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_pdo_2->getSeguimientoItemplan('','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_SEGUIMIENTO_PDO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_seguimiento_pdo_2',$data);
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
                            <th style="text-align: center;" colspan="3">OBRAS</th>
                            <th style="TEXT-ALIGN: center;" colspan="3">MAT_COAX</th>
                            <th style="TEXT-ALIGN: center;" colspan="3">MAT_FO</th>                                                           
                       </tr>
                       <tr role="row">                           
                            <th colspan="1">JEFATURA</th>
                            <th colspan="1">PROYECTO</th>
                            <th colspan="1">SUBPROYECTO</th>                            
                            
                            <th colspan="1">TOTAL</th>                          
                            <th colspan="1">PENDIENTES</th>
	                        <th colspan="1">TERMINADOS</th>
	                    
                            <th colspan="1">DISEÑO</th>
                            <th colspan="1">B. APROB</th>      
                            <th colspan="1">OPER</th>
                            
                            <th colspan="1">DISEÑO</th>
                            <th colspan="1">B. APROB</th>      
                            <th colspan="1">OPER</th>
                                        
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){                   
                    
                $html .=' <tr>
                            <td>'.$row->jefatura.'</td>
                            <td>'.$row->proyectoDesc.'</td>
							<td>'.$row->subProyectoDesc.'</td>
							    
							<td>'.$row->total.'</td>
							<td>'.($row->total - $row->terminados).'</td>							    	
                            <td><a style="color:blue" data-tipo="MAT_COAX" data-grupo="3" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->terminados.'</td>
							    
							<td><a style="color:blue" data-tipo="MAT_COAX" data-grupo="4" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.($row->total - ($row->terminados + $row->oper_mat_coax + $row->pendiente_mat_coax)).'</td>
							<td><a style="color:blue" data-tipo="MAT_COAX" data-grupo="1" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->pendiente_mat_coax.'</td>							
							<td><a style="color:blue" data-tipo="MAT_COAX" data-grupo="2" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->oper_mat_coax.'</td>
                                
							<td><a style="color:blue" data-tipo="MAT_FO" data-grupo="4" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.($row->total - ($row->terminados + $row->oper_mat_fo + $row->pendiente_mat_fo)).'</td>
							<td><a style="color:blue" data-tipo="MAT_FO" data-grupo="1" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->pendiente_mat_fo.'</a></td>							
							<td><a style="color:blue" data-tipo="MAT_FO" data-grupo="2" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->oper_mat_fo.'</td>
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return $html;
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
            $subProyecto = $this->input->post('subProy');
            $region = $this->input->post('zonal');         
            $fase = $this->input->post('mes');           
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_pdo_2->getSeguimientoItemplan($idProyecto,$subProyecto,$region,$fase));
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

    function getDetalle(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            $proyectoDesc = $this->input->post('proyecto');
            $subProyectoDesc = $this->input->post('subProy');
            $jefatura = $this->input->post('jefatura');
            $tipo = $this->input->post('tipo');         
            $grupo = $this->input->post('grupo');
            if($tipo== 'MAT_FO' && $grupo==PENDIENTE_NO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getPendientesNoTerminadosMatFo($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==PENDIENTE_NO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getPendientesNoTerminadosMatCoax($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==APROBADO_NO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getAprobadosNoTerminadoMatFo($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==APROBADO_NO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getAprobadosNoTerminadoMatCoax($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==APROBADO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getAprobadosTerminadoMatCoax($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==NO_TIENE_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getDisenoMatFo($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==NO_TIENE_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_2->getDisenoMatCoax($proyectoDesc,$subProyectoDesc,$jefatura),$tipo,$grupo);
            }
            $data['error']    = EXIT_SUCCESS;
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
   public function makeHTLMTablaItemplanDet($listaPTR, $tipo, $grupo){
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ItemPlan</th>';
            if($grupo==APROBADO_TERM){
                $html .= '<th>Fecha Termino</th>';                
            }else if($grupo==PENDIENTE_NO_TERM || $grupo==APROBADO_NO_TERM){
                $html .= '<th>ptr</th>
                          <th>Estado</th>';
                if($grupo==PENDIENTE_NO_TERM){
                   $html .= '<th>Grafo</th>';
                } else if($grupo==APROBADO_NO_TERM){
                   $html .= '<th>Vale Reserva</th>';
                }  
            }
        
           $html .= ' </tr>
                    </thead>
          
                    <tbody>';
         
        foreach($listaPTR->result() as $row){
            $html .= '  <tr>
                        <td>'.$row->itemplan.'</td>';
            if($grupo==APROBADO_TERM){
                $html .= '  <th>'.$row->fechaTermino.'</th>';
            }else if($grupo==PENDIENTE_NO_TERM || $grupo==APROBADO_NO_TERM){
                    if($tipo=='MAT_FO'){
                $html .= '  <th>'.$row->mat_fo_ptr.'</th>
                            <th>'.substr($row->mat_fo_est,0,3).'</th>';
                     }else if($tipo=='MAT_COAX'){
                $html .=  ' <th>'.$row->mat_coax_ptr.'</th>
                            <th>'.substr($row->mat_coax_est,0,3).'</th>';
                }
                if($grupo==PENDIENTE_NO_TERM){
                    $html .=  ' <th>'.$row->grafo_wu.'</th>';
                }else if($grupo==APROBADO_NO_TERM){
                    $html .=  ' <th>'.$row->vale_reserva.'</th>';
                }
            }
            $html .='</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
}