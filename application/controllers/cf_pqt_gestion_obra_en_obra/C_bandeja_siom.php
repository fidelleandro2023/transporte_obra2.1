<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_siom extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_plan_obra/m_pqt_bandeja_siom');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_BANDEJA_SIOM);
        	   $data['tablaSiom']     = $this->getTablaSiom($itemplan);
        	   $data['opciones'] = $result['html'];
        	   $this->load->view('vf_pqt_gestion_obra_en_obra/v_bandeja_siom',$data);
        	   /*
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_pqt_gestion_obra_en_obra/v_bandeja_siom',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }*/
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaSiom($itemplan) {
        $data = $this->m_pqt_bandeja_siom->getBandejaSiom($itemplan);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>SubProyecto</th>
                            <th>ptr</th>                            
                            <th>Fec. registro</th>                         
                            <th>Estacion</th>
                            <th>Avance</th>
                            <th>Estado Plan</th>
                            <th>Jefatura</th>
                            <th>MDF</th>
                            <th>EECC</th>
                            <th>Codigo Siom</th>                            
                            <th>Ultimo Estado</th>
                            <th>Fec. Ultimo Estado</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){      
                $html .=' <tr>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->fechaRegistro.'</td>
                            <td>'.$row->estacionDesc.'</td>
                            <td>'.(($row->porcentaje==null) ? 0 : $row->porcentaje).'</td>       
                            <td>'.utf8_decode($row->estadoPlanDesc).'</td>
                            <td>'.$row->jefatura.'</td>
                            <td>'.$row->codigo.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
                            <td>'.$row->codigoSiom.'</td>
                            <th>'.$row->ultimo_estado.'</th>
                            <th>'.$row->fecha_ultimo_estado.'</th>
                        </tr>';
                }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    function getDataSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idSiomObra = $this->input->post('id_siom');
    
            if($idSiomObra == null) {
                throw new Exception("ERROR idSiom No Valido");
            }
            $dataSiom =  $this->m_bandeja_siom->getDataSiomByIdSiomObra($idSiomObra);
            if($dataSiom    ==  null){
                throw new Exception("ERROR No se encontra Informacion.");
            }
            
            $listaEstado = $this->m_bandeja_siom->listaLogEstadosSIom($dataSiom['codigoSiom']);
            if(count($listaEstado)>=1){
                $tipo = 2;
            }else{
                $tipo = 1;
            }
            
            $tablaSiom = $this->getTablaConsultaSiom($listaEstado, $dataSiom, $tipo);
            $data['error'] = EXIT_SUCCESS;
            $data['tablaSiom'] = $tablaSiom;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}