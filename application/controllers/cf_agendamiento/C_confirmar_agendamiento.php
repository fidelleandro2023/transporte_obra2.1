<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_confirmar_agendamiento extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_agendamiento/m_agendamiento');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	
            $data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_AGENDAMIENTO, ID_PERMISO_HIJO_CONFIRMAR_AGENDA);
            $data['title'] = 'CONFIRMAR AGENDAMIENTO';
            // $data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['tablaAgendamiento'] = $this->getTablaAgendamiento();
            $data['opciones'] = $result['html'];
            $this->load->view('vf_agendamiento/v_confirmar_agendamiento',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getTablaAgendamiento() {
        $arrayDataAgen = $this->m_agendamiento->getAgendamiento(NULL);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white ; background-color: #3b5998">Itemplan</th>
                            <th style="color: white ; background-color: #3b5998">Jefatura</th>  
                            <th style="color: white ; background-color: #3b5998">EECC</th>
                            <th style="color: white ; background-color: #3b5998">Banda Horaria</th>                            
                            <th style="color: white ; background-color: #3b5998">Usuario Registro</th>
                            <th style="color: white ; background-color: #3b5998">Fecha Agendamiento</th>   
                            <th style="color: white ; background-color: #3b5998">Fecha Registro</th> 
                            <th style="color: white ; background-color: #3b5998">Estado</th>   
                            <th style="color: white ; background-color: #3b5998">Confirmar</th>                                                                             
                        </tr>
                    </thead>                    
                    <tbody>';
        $val = 0;
        foreach($arrayDataAgen as $row) {
            
            if($row['flg_estado'] == FLG_CONFIRMADO || $row['flg_estado'] == FLG_CANCELADO) {
                $btnConfirmar = null;
                $btnCancelar  = null; 
                $caldedario = $row['fecha_agendamiento'];
                $style = null;
            } else {
                $style = '#F2F5A9';
                $btnCancelar = '<button class="btn btn-danger" data-id_agendamiento="'.$row['idAgendamiento'].'" data-val="'.$val.'" onclick="openModalConfirmarCancelacion($(this));">Cancelar</button>';                                
                $btnConfirmar = '<button class="btn btn-success" data-id_agendamiento="'.$row['idAgendamiento'].'" data-val="'.$val.'" onclick="openModalConfirmarAgendamiento($(this));">Confirmar</button>';                
                $caldedario = '<input id="fecha_agendamiento_'.$val.'" type="date" class="form-control" value="'.$row['fecha_agendamiento'].'"'; 
            }

            $val++;
            $html .='   <tr>
                            <td style="color: white ; background-color: #3b5998">'.$row['itemplan'].'</td>
                            <td>'.$row['jefatura'].'</td>
                            <td>'.$row['empresaColabDesc'].'</td>							
                            <th>'.$row['bandaHoraria'].'</th>
                            <th>'.$row['usuarioRegistro'].'</th>
                            <th style="background-color: '.$style.'">'.$caldedario.'</th>		
                            <th>'.$row['fecha_registro'].'</th>
                            <th>'.$row['estado'].'</th>	
                            <th>'.$btnConfirmar.' '.$btnCancelar.'</th>				                                                    				                        
                        </tr>';
        }
        $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function confirmarAgendamiento() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['submsj'] = null;
        try {
            $fechaAgendamiento = $this->input->post('fechaAgendamiento');
            $idAgendamiento    = $this->input->post('idAgendamiento');            

            $dataArray = array(
                'fecha_agendamiento'    => $fechaAgendamiento,
                'idUsuarioConfirmacion' => $this->session->userdata('idPersonaSession'),
                'fecha_confirmacion'    => $this->fechaActual(),
                'flg_estado'            => FLG_CONFIRMADO
            );

            $data = $this->m_agendamiento->updateConfirmarAgendamiento($dataArray, $idAgendamiento);
            $data['tablaAgendamiento'] = $this->getTablaAgendamiento();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function getAgendamientosCalendar() {
        $arrayDataAgen = $this->m_agendamiento->getAgendamiento(NULL);
        $arry = array();
        $val = 1;
        foreach($arrayDataAgen as $row) {
            $rw = array();
            $rw['id']    = $val;
            $rw['title'] = 'Agendamiento '.$row['fecha_agendamiento'];
            $rw['class'] = "event-success";
            $rw['start'] = $row['fechaMilisec'];
            array_push($arry, $rw);
            $val++;
        }

        echo json_encode($arry, JSON_NUMERIC_CHECK);
    }

    function getDetalleAgendamientoByFecha() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $fecha = ($this->input->post('fecha') == null ? date('Y-m-d') : date('Y-m-d', ($this->input->post('fecha') / 1000) ) );

            if($fecha == null) {
                throw new Exception("ND fecha");
            }
            $data['error'] = EXIT_SUCCESS;
            $arrayDataAgen = $this->m_agendamiento->getAgendamiento($fecha);
            $tablaModalDetalleAgen = $this->getTablaDetalleAgendamiento($arrayDataAgen);
            $data['tablaDetalleAgenda'] = $tablaModalDetalleAgen;
            $data['fechaAgen'] = $fecha;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function confirmarCancelacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['submsj'] = null;
        try {
            $idAgendamiento    = $this->input->post('idAgendamiento');            

            $dataArray = array(
                'idUsuarioConfirmacion' => $this->session->userdata('idPersonaSession'),
                'fecha_confirmacion'    => $this->fechaActual(),
                'flg_estado'            => FLG_CANCELADO
            );

            $data = $this->m_agendamiento->updateConfirmarAgendamiento($dataArray, $idAgendamiento);
            $data['tablaAgendamiento'] = $this->getTablaAgendamiento();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}