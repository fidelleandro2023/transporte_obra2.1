<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_seguimiento_avance extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_seguimiento_avance');
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
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO(null,'');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_SEGUIMIENTO_AVANCE_PO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_seguimiento_avance',$data);
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
    public function makeHTLMTablaSeguimientoPDO($listDatos,$region){
       
        $html = '
                  <table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                      
                       <tr role="row">                           
                          
                            <th colspan="1">ITEMPLAN</th>
                            <th colspan="1">INDICADOR</th>';
                        if($region=='LIMA'){
                            $html .= '<th colspan="1">OPERACION</th>';
                        }else{
                                 $html .= '<th colspan="1">JEFATURA</th>';
                        }
                                                                                   
                  $html .= '<th colspan="1">FASE</th>
                            <th colspan="1">SUB PROYECTO</th>
                            <th colspan="1">ESTADO</th> 
	                        <th colspan="1">% PROM. AVANCE</th>
	                    
                            <th colspan="1">COAXIAL</th>
	                        <th colspan="1">OC_COAXIAL</th>
                            <th colspan="1">FUENTE</th>  
                            <th colspan="1">UM</th>
                            <th colspan="1">FO</th>
	                        <th colspan="1">OC_FO</th>
                            <th colspan="1">ENERGIA</th>       
                            <th colspan="1">MULTIPAR</th>
	                        <th colspan="1">TROBA</th>
                            <th colspan="1">CALIBRACION</th>           
                        </tr>
                    </thead>                    
                    <tbody>';
        if($listDatos!=null){													                                                   
                foreach($listDatos->result() as $row){                   
                    $color = '';
                  
                    if($row->prom >= 0 && $row->prom<=49){
                        $color = 'background-color:red; color:white';
                    }else if($row->prom>=50 && $row->prom<=74){
                        $color = 'background-color:yellow';
                    }else if($row->prom>=75 && $row->prom<=99){
                        $color = 'background-color:orange; color:white';
                    }else if($row->prom==100){
                        $color = 'background-color:green; color:white';
                    }
                $html .=' <tr>
                           
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->indicador.'</td>';
                if($region=='LIMA'){
                    $html .= '<td>'.$row->empresaColabDesc.'</td>';
                }else{
                    $html .= '<td>'.$row->jefatura.'</td>';
                }
							
               $html .= '   <td>'.$row->faseDesc.'</td>
                            <td>'.$row->subproyectoDesc.'</td>
                            <td>'.utf8_decode($row->estadoPlanDesc).'</td>
                            <td style="'.$color.'; text-align: center;">'.(($row->prom=='') ? '0%' :$row->prom.'%').'</td>
                            <td '.(($row->coaxial=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->coaxial)).'>'.(($row->coaxial=='NO') ? '' : (($row->coaxial=='') ? '0%' : $row->coaxial.'%')).'</td>
                            <td '.(($row->oc_coaxial=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->oc_coaxial)).'>'.(($row->oc_coaxial=='NO') ? '' : (($row->oc_coaxial=='') ? '0%' : $row->oc_coaxial.'%')).'</td> 
                            <td '.(($row->fuente=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->fuente)).'>'.(($row->fuente=='NO') ? '' : (($row->fuente=='') ? '0%' : $row->fuente.'%')).'</td>
                            <td '.(($row->um=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->um)).'>'.(($row->um=='NO') ? '' : (($row->um=='') ? '0%' : $row->um.'%')).'</td>
                            <td '.(($row->fo=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->fo)).'>'.(($row->fo=='NO') ? '' : (($row->fo=='') ? '0%' : $row->fo.'%')).'</td>
                            <td '.(($row->oc_fo=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->oc_fo)).'>'.(($row->oc_fo=='NO') ? '' : (($row->oc_fo=='') ? '0%' : $row->oc_fo.'%')).'</td>
                            <td '.(($row->energia=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->energia)).'>'.(($row->energia=='NO') ? '' : (($row->energia=='') ? '0%' : $row->energia.'%')).'</td>
                            <td '.(($row->multipar=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->multipar)).'>'.(($row->multipar=='NO') ? '' : (($row->multipar=='') ? '0%' : $row->multipar.'%')).'</td>
                            <td '.(($row->inst_troba=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->inst_troba)).'>'.(($row->inst_troba=='NO') ? '' : (($row->inst_troba=='') ? '0%' : $row->inst_troba.'%')).'</td>
                            <td '.(($row->inte_troba=='NO') ? 'style="background-color: #808080c2;"' : $this->getColorText($row->inte_troba)).'>'.(($row->inte_troba=='NO') ? '' : (($row->inte_troba=='') ? '0%' : $row->inte_troba.'%')).'</td>

                		</tr>';
                 }
        }
			 $html .='</tbody>
                </table>';
                    
        return $html;
    }
    
    function getColorText($valor){
        $color = 'style="color:gray;text-align: center;font-weight: bold;"';
        if($valor >= 0 && $valor<=49){
            $color = 'style="color:red;text-align: center;font-weight: bold;"';
        }else if($valor>=50 && $valor<=74){
            $color = 'style="color:#b7b714;text-align: center;font-weight: bold;"';
        }else if($valor && $valor<=99){
            $color = 'style="color:orange;text-align: center;font-weight: bold;"';
        }else if($valor==100){
            $color = 'style="color:green;text-align: center;font-weight: bold;;"';
        }
        return $color;
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
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_seguimiento_avance->getSeguimientoItemplan($idProyecto,$subProyecto,$region,$fase,$mes,$idFase),$region);
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
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getTerminados($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==CON_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getConPTRMatFo($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==CON_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getConPTRMatCoax($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==APROBADO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getAprobadosMatFo($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==APROBADO_TERM){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getAprobadosMatCoax($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_FO' && $grupo==NO_TIENE_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getDisenoMatFo($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
            }else if($tipo== 'MAT_COAX' && $grupo==NO_TIENE_PTR){
                $data['tablaDetalleItem'] = $this->makeHTLMTablaItemplanDet($this->m_seguimiento_avance->getDisenoMatCoax($proyectoDesc,$subProyectoDesc,$jefatura,$mes,$fase,$eecc),$tipo,$grupo);
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
            $listaEstacion = $this->m_seguimiento_avance->getEstacionPorcentajeByItemPlan($itemplan)->result();          
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