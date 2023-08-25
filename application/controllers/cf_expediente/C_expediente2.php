<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_expediente2 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_expediente/m_expediente2');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonalGroup();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_expediente2->getPtrToLiquidacion('','','','SI','','',''));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_BANDEJA_APROB);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_expediente/v_expediente2',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function asignarExpediente(){
        $logedUser = $this->session->userdata('usernameSession');

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $jsonptr = $this->input->post('jsonptr');
            $comentario = $this->input->post('comentario');

            $arrayPTRItem = json_decode($jsonptr, true);
            $data = $this->m_expediente2->insertExpediente($comentario,$logedUser);


            foreach ($arrayPTRItem as $row) {
                $subrows = explode("%", $row);
                $ptr = $subrows[0];
                $item = (($subrows[1] != null) ? $subrows[1] : null);
                $fecsol = $subrows[2];
                $subproyecto = $subrows[3];
                $zonal = $subrows[4];
                $eecc = $subrows[5];
                $area = $subrows[6];

                //aquir recibir en una variable la wu.f_ult_est para enviar al insert()

                $this->m_expediente2->insertPTR($ptr,$item,$fecsol,$subproyecto,$zonal,$eecc,$area);

            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaAsignarGrafo($listaPTR){
     
        $html = ' <button class="btn btn-primary" onclick="recogePTR();">REGISTRAR SELECCIONADOS</button>

            


                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Seleccionar</th>
                            <th>Itemplan</th>
                            <th>PTR</th>                            
                            <th>Monto MAT</th>
                            <th>Monto MO</th>
                            <th>Subproyecto</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fec Sol.</th>
                            <th>Tpo. Esp</th>
                            <th>Fec. Prevista</th>
                            <th>Area</th>                            
                        </tr>
                    </thead>
                    
                    <tbody>';
		   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' 
                        <tr>
                            <th style="text-align: center">'.(($row->id_expediente == null) ? '<div class="btn-group btn-group--colors" data-toggle="buttons">
                                <label class="btn bg-cyan"><input type="checkbox" data-ptr ="'.$row->ptr.'" data-item ="'.$row->itemPlan.'" data-fecsol="'.$row->f_ult_est.'" data-subproyecto="'.$row->subProyectoDesc.'" data-zonal="'.$row->jefatura.'" data-eecc="'.$row->eecc.'" data-area="'.$row->desc_area.'" name="ptrExp[]" autocomplete="off"></label>
                            </div>' : '<span style="font-size: 10px" class="badge badge-pill badge-default">CON EXPEDIENTE</span>').'</th>
                                
                            
							<td><a href="detalleObra?item='.$row->itemPlan.'" target="_blank">'.$row->itemPlan.'</a></td>
							<td>'.$row->ptr.'</td>	
							<td>'.$row->valoriz_material.'</td>
							<td>'.$row->valoriz_m_o.'</td>
							<td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->jefatura.'</td>
							<th>'.$row->eecc.'</th>
							<th>'.$row->f_ult_est.'</th> 
                            <th>'.substr($row->tpo_espera,0,-3).'</th>

                            <th>'.$row->fechaPrevEjec.'</th>                              
                            <td>'.$row->desc_area.'</td>                            
						</tr>
                        ';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');
            //$estado = $this->input->post('estado');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_expediente2->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area));
            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}