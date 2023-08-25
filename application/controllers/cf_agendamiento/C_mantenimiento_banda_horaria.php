<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_mantenimiento_banda_horaria extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_agendamiento/m_mantenimiento_banda_horaria');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    // if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_AGENDAMIENTO, ID_PERMISO_HIJO_BANDA_HORARIA);
            $data['title'] = 'MANTENIMIENTO BANDA HORARIA';
            // $data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['tablaBandaHoraria'] = $this->tablaBandaHoraria(null);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_agendamiento/v_mantenimiento_banda_horaria',$data);
	//    }else{
	//        redirect('login','refresh');
	//    }
    }

    function tablaBandaHoraria($idBandaHoraria) {
        // $idEcc     = $this->session->userdata('eeccSession');
        $data = $this->m_mantenimiento_banda_horaria->getBandaHoraria($idBandaHoraria);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white ; background-color: #3b5998" width="10%">#</th>
                            <th style="color: white ; background-color: #3b5998">Fecha Inicio</th>
                            <th style="color: white ; background-color: #3b5998">Fecha Fin</th>
                            <th style="color: white ; background-color: #3b5998">Banda Horaria</th>   
                            <th style="color: white ; background-color: #3b5998">Acci&oacute;n</th>                                                                                                                                                                                                              
                        </tr>
                    </thead>                    
                    <tbody>';
        $cont=0;
        foreach($data as $row){
            $btnEliminar = '<a data-id_banda_horaria="'.$row['idBandaHoraria'].'"onclick="openModalAlertaEliminarBandaHoraria($(this))"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>';
            $cont++;
            $html .='   <tr>
                            <td style="color: white ; background-color: #3b5998">'.$cont.'</td>
                            <td>'.$row['horaInicio'].'</td>
                            <td>'.$row['horaFin'].'</td>							
                            <th>'.$row['horaInFin'].'</th>	
                            <th>'.$btnEliminar.'</th>			                                                    				                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function registrarBandaHoraria() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR; 
        try {
            $horaInicio = $this->input->post('horaInicio');
            $horaFin    = $this->input->post('horaFin');

            if($horaInicio == null || $horaFin == null) {
                throw new Exception("comunicarse con el programador");
            }
            $arrayData = array( 
                'horaInicio' => $horaInicio,
                'horaFin'    => $horaFin,
                'estado'     => FLG_ACTIVO
            );
            $data = $this->m_mantenimiento_banda_horaria->insertBandaHoraria($arrayData);
            $data['tablaBandaHoraria'] = $this->tablaBandaHoraria(null);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    function eliminarBandaHoraria() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR; 
        try {
            $idBandaHoraria = $this->input->post('idBandaHoraria');

            if($idBandaHoraria == null || $idBandaHoraria == null) {
                throw new Exception("comunicarse con el programador");
            }

            $data = $this->m_mantenimiento_banda_horaria->deleteBandaHoraria($idBandaHoraria);
            $data['tablaBandaHoraria'] = $this->tablaBandaHoraria(null);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}