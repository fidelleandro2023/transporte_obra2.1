<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_seguimiento_pdo_3 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_seguimiento_pdo_3');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();        	           
        	   $data['listaRegion'] = $this->m_utils->getAllRegion();
        	   $data['listafase'] = $this->m_utils->getAllFase();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_pdo_3->getSeguimientoItemplan('','','','','',''),'');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_CUADRO_DE_MANDO_I);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_seguimiento_pdo_3',$data);
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
    public function makeHTLMTablaSeguimientoPDO($listDatos,$isLima){
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
                            <th style="text-align: center;" colspan="4">DESCRIPCION</th>
                            <th style="text-align: center;" colspan="3">OBRAS</th>
	                        <th style="TEXT-ALIGN: center;    background-color: #6565ff;" colspan="3">MAT_FO</th>
                            <th style="TEXT-ALIGN: center;    background-color: #ffa5009e;" colspan="3">MAT_COAX</th>
                                                                                       
                       </tr>
                       <tr role="row">
                            <th colspan="1">FASE</th>  
                            <th colspan="1">'.(($isLima=='LIMA') ? 'OPERACION': 'JEFATURA').'</th>
                            <th colspan="1">PROYECTO</th>
                            <th colspan="1">SUBPROYECTO</th>                            
                            
                            <th colspan="1">TOTAL</th>                          
                            <th colspan="1">PENDIENTES</th>
	                        <th colspan="1">TERMINADOS</th>
	                    
                            <th colspan="1">CON PTR</th>
	                        <th colspan="1">APROB. VR</th>
                            <th colspan="1">SIN PTR</th>      
                            
                            
                            <th colspan="1">CON PTR</th>
	                        <th colspan="1">APROB. VR</th>
                            <th colspan="1">SIN PTR</th>       
                                        
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){                   
                    
                $html .=' <tr>
                            <td>'.$row->faseDesc.'</td>
                            <td>'.(($isLima=='LIMA') ? $row->empresaColabDesc: $row->jefatura).'</td>
                            <td>'.$row->proyectoDesc.'</td>
							<td>'.$row->subProyectoDesc.'</td>
							    
							<td>'.$row->total.'</td>
							<td>'.($row->total - $row->terminados).'</td>							    	
                            <td><a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-grupo="6" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->terminados.'</td>

                            <td><a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-tipo="MAT_FO" data-grupo="5" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->con_ptr_fo.'</td>
                            <td><a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-tipo="MAT_FO" data-grupo="3" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->con_vr_fo.'</td>
                            <td><a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-tipo="MAT_FO" data-grupo="4" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.($row->total - $row->con_ptr_fo).'</td>
							
                            <td'.(($row->hasMatCoax=='0')? ' style="background-color: #808080c2;"' : '').'>'.(($row->hasMatCoax=='0')? '' : '<a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-tipo="MAT_COAX" data-grupo="5" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->con_ptr_coax).'</td>
                            <td'.(($row->hasMatCoax=='0')? ' style="background-color: #808080c2;"' : '').'>'.(($row->hasMatCoax=='0')? '' : '<a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-tipo="MAT_COAX" data-grupo="3" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.$row->con_vr_coax).'</td>
                            <td'.(($row->hasMatCoax=='0')? ' style="background-color: #808080c2;"' : '').'>'.(($row->hasMatCoax=='0')? '' : '<a style="color:blue" data-eecc="'.(($isLima=='LIMA') ? $row->idEmpresaColab: 0).'" data-tipo="MAT_COAX" data-grupo="4" data-jef="'.$row->jefatura.'" data-pro="'.$row->proyectoDesc.'" data-sub="'.$row->subProyectoDesc.'" onclick="getDetalle(this)">'.($row->total - $row->con_ptr_coax)).'</td>
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
            $fase = $this->input->post('fase');  
            $mes = $this->input->post('mes');
            $idFase = $this->input->post('idFase');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_pdo_3->getSeguimientoItemplan($idProyecto,$subProyecto,$region,$fase,$mes,$idFase),$region);
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
            $fase = $this->input->post('fase');
            $mes = $this->input->post('mes'); 
            $eecc = $this->input->post('eecc'); 
            if($grupo==TERMINADOS_ALL){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getTerminados($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==CON_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getConPTRMatFo($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==CON_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getConPTRMatCoax($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==APROBADO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getAprobadosMatFo($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==APROBADO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getAprobadosMatCoax($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==NO_TIENE_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getDisenoMatFo($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==NO_TIENE_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_pdo_3->getDisenoMatCoax($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }
            $data['error']    = EXIT_SUCCESS;
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
   public function makeHTLMTablaItemplanDet($listaPTR, $tipo, $grupo){
        $html = '<table id="data-table2" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>
                            <th>ItemPlan</th>
                            <th>Sub Proyecto</th>';
            if($grupo==APROBADO_TERM){
                $html .= '  <th>Ptr</th>
                            <th>VR</th>
                            <th>Fec Aprob.</th>';                
            }
            if($grupo==CON_PTR){
                $html .= '  <th>Ptr</th>';
            }
            if($grupo==TERMINADOS_ALL){
                $html .= '  <th>Fec. Termino</th>';
            }
           $html .= ' <th>Indicador</th>';
           if($grupo==APROBADO_TERM){
               $html .=  ' <th>Porcentaje</th>';
           }
               
          $html .=  '</tr></thead>          
                        <tbody>';
         
        foreach($listaPTR->result() as $row){
            $html .= '  <tr>
                        <td>'.$row->itemplan.'</td>
                        <td>'.$row->subProyectoDesc.'</td>';
            
            if($grupo==APROBADO_TERM){
               
                if($tipo=='MAT_FO'){
                    $html .= '  <th>'.$row->mat_fo_ptr.'</th>';
                         
                }else if($tipo=='MAT_COAX'){
                    $html .=  ' <th>'.$row->mat_coax_ptr.'</th>';                            
                 }
                 $html .=  ' <th>'.$row->vale_reserva.'</th>';
                 $html .=  ' <th>'.$row->fec_aprob.'</th>';
              
            }
            if($grupo==CON_PTR){
                if($tipo=='MAT_FO'){
                    $html .= '  <th>'.$row->mat_fo_ptr.'</th>';
                     
                }else if($tipo=='MAT_COAX'){
                    $html .=  ' <th>'.$row->mat_coax_ptr.'</th>';
                }
                
            }
            if($grupo==TERMINADOS_ALL){
                $html .= '<th>'.$row->fechaTermino.'</th>';
            }
            $html .='<td>'.$row->indicador.'</td>';
            
            if($grupo==APROBADO_TERM){         
                $color = '';
               if($row->prom<=24){
                    $color = 'red';
                }else if($row->prom>=25 && $row->prom<=74){
                    $color = 'yellow';
                }else if($row->prom>=75 && $row->prom<=99){
                    $color = 'orange';
                }else if($row->prom==100){
                    $color = 'green';
                }
                
                
                $html .=  ' <th style="background-color:'.$color.'"><a data-item="'.$row->itemplan.'" style="color:blue" onclick="getDetallePor(this)">'.(($row->prom!='') ? $row->prom.'%' : '0%').'</a></th>';
            }
        }
        $html .='</tr></tbody>
                </table>';
    
        return utf8_decode($html);
    }

    function getPorcentajeEstacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $listaEstacion = $this->m_seguimiento_pdo_3->getEstacionPorcentajeByItemPlan($itemplan)->result();          
            $data['htmlEstaciones'] = $this->makeHtmlContEstaciones($listaEstacion);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     function makeHtmlContEstaciones($listaEstaciones){
        $html='';
        foreach($listaEstaciones as $row){
            $color = '';
            if($row->porcentaje=='NR'){
                $color = '#b7b7b7';
            }else 
            if($row->porcentaje==0){
                $color = 'red';
            }else if($row->porcentaje==25){
                $color = 'red';
            }else if($row->porcentaje==50){
                $color = 'yellow';
            }else if($row->porcentaje==75){
                $color = 'orange';
            }else if($row->porcentaje==100){
                $color = 'green';
            }
            
            $html .= ' 	<div class="col-sm-6 col-md-6">
                     	<div class="form-group" style="text-align:center">
                                    <label>'.$row->estacionDesc.'</label>
                                    <input style="text-align:center;background-color: '.$color.'" type="text" class="form-control" value="'.(($row->porcentaje=='NR') ? 'NO REQUIERE' : $row->porcentaje."'%'").'"></input>                                   
                        </div>
                    </div>';
        }
    
        return $html;
    }
    
}