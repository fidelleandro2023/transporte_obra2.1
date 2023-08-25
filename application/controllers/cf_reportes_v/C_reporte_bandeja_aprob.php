<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_reporte_bandeja_aprob extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_reporte_bandeja_aprob');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();        	           
        	  // $data['listaRegion'] = $this->m_utils->getAllRegion();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_reporte_bandeja_aprob->getSeguimientoBandejaAprob('','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_REPORTE_BANDEJA_APROB);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_reporte_bandeja_aprob',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
 
    public function makeHTLMTablaSeguimientoPDO($listDatos){
    	$listaJson = json_encode($listDatos->result());

        $comiListaJson = "'".$listaJson."'";

        $html = '<!--<button class="btn" style="background-color: #28B463; color: white; padding: 10px" onclick="bajar()">Descargar Excel</button>-->
        	
                  <table id="data-table" class="table table-bordered" style="font-size: 11px;">
                    <thead class="thead-default">
                      
                       <tr role="row">                           
                            <th colspan="1" style="color: white; background-color: #285177"><STRONG>AREA</STRONG></th>
                            <th colspan="1" style="color: white; background-color: #285177"><STRONG>SUB PROYECTO</STRONG></th>
                            <th colspan="1"style="color: white; background-color: #285177; text-align: center"><STRONG>CON GRAFO</STRONG></th>                            
                            
                            <th colspan="1" style="color: white; background-color: #285177; text-align: center"><STRONG>SIN GRAFO</STRONG></th>                          
                            <th colspan="1" style="color: white; background-color: #285177; text-align: center"><STRONG>SIN PRESUPUESTO</STRONG></th>
	                        <th colspan="1"style="color: white; background-color: #285177"><STRONG>TOTAL</STRONG></th>
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listDatos->result() as $row){                   
                    
                $html .=' <tr>
                            
							<td style="color: black;">'.$row->desc_area.'</td>							    	
                            <td style="color: black;">'.$row->subproy.'</td>		
                            <td><a data-opc="1" data-area="'.$row->desc_area.'" data-subp="'.$row->subproy.'" onclick="getDetalle(this)" style="color: blue">'.$row->con_grafo.'</a></td>		
                            <td><a data-opc="2" data-area="'.$row->desc_area.'" data-subp="'.$row->subproy.'" onclick="getDetalle(this)" style="color: blue">'.$row->sin_grafo.'</a></td>		
                            <td><a data-opc="3" data-area="'.$row->desc_area.'" data-subp="'.$row->subproy.'" onclick="getDetalle(this)" style="color: blue">'.$row->sin_presupuesto.'</a></td>		
                            <td data-opc="4"'.$row->desc_area.'""'.$row->subproy.' style="color:black">'.($row->con_grafo+$row->sin_grafo+$row->sin_presupuesto).'</td>		
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
            $area = $this->input->post('area');         
            $fase = $this->input->post('fase');  
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaSeguimientoPDO($this->m_reporte_bandeja_aprob->getSeguimientoBandejaAprob($idProyecto,$subProyecto,$fase,$area));
            $data['error']    = EXIT_SUCCESS;
          
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }



    public function makeHTLMTablaDetalle($listaPTR){
        $html = '<table id="data-table2" class="table table-bordered" style="font-size: 11px;">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white; background-color: #285177">PTR</th>
                            <th style="color: white; background-color: #285177">ITEMPLAN</th>
                            <th style="color: white; background-color: #285177">INDICADOR</th>
                            <th style="color: white; background-color: #285177">Sub Proyecto</th>
                            <th style="color: white; background-color: #285177">PEP</th>                            
                            <th style="color: white; background-color: #285177">GRAFO</th>
                            <th style="color: white; background-color: #285177">Valor de MAT</th>
                            <th style="color: white; background-color: #285177">Valor de MO</th>
                        </tr>
                    </thead>          
                        <tbody>';

        foreach($listaPTR->result() as $row){
            $html .= ' <tr>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->itemPlan.'</td>
                            <td>'.$row->indicador.'</td>
                            <td>'.$row->subproy.'</td>
                            <td>'.$row->pep.'</td>
                            <td>'.$row->grafo.'</td>
                            <td>'.$row->valor_material.'</td>
                            <td>'.$row->valor_m_o.'</td>
                       </tr>';
        }
        $html .='</tbody>
                </table>';

        return utf8_decode($html);
    }




  // ESTO CONVERSA CON EL MODAL DETALLE  SU TABLA DE MODAL

    function getDetalleBA(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $areadesc = $this->input->post('area');
            $subProyectoDesc = $this->input->post('subproyecto');
            $opcion = $this->input->post('opcion');

            if($opcion=='1'){
                $data['tablaDetalleBA'] = $this->makeHTLMTablaDetalle($this->m_reporte_bandeja_aprob->getPtrConGrafo($areadesc, $subProyectoDesc));
            }else if($opcion=='2'){
                //sin grafo
                $data['tablaDetalleBA'] = $this->makeHTLMTablaDetalle($this->m_reporte_bandeja_aprob->getPtrSinGrafo($areadesc, $subProyectoDesc));
            }else if($opcion=='3'){
                //sin presupuesto
                $data['tablaDetalleBA'] = $this->makeHTLMTablaDetalle($this->m_reporte_bandeja_aprob->getPtrSinPresupuesto($areadesc, $subProyectoDesc));
            }else if($opcion=='4'){
                //todos
                $data['tablaDetalleBA'] = $this->makeHTLMTablaDetalle($this->m_reporte_bandeja_aprob->getPtrtotal($areadesc, $subProyectoDesc));
            }
            $data['error']    = EXIT_SUCCESS;

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }





} // LLAVE FINAL