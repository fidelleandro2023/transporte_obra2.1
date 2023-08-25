<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_matriz_agendamiento extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_agendamiento/m_matriz_agendamiento');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index() {	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_AGENDAMIENTO, ID_PERMISO_HIJO_MATRIZ_CUOTAS);
            $data['title'] = 'MATRIZ AGENDAMIENTO';
            // $data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['tablaMatriz'] = $this->tablaMatriz();
            $data['opciones'] = $result['html'];
            $this->load->view('vf_agendamiento/v_matriz_agendamiento',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function tablaMatriz() {
        $idEcc     = $this->session->userdata('eeccSession');
        $data = $this->m_matriz_agendamiento->getMatrizAgendamiento($idEcc);
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white ; background-color: #3b5998" width="10%"></th>
                            <th style="color: white ; background-color: #3b5998">COBRA</th>                            
                            <th style="color: white ; background-color: #3b5998">LARI</th>
                            <th style="color: white ; background-color: #3b5998">DOMINION</th>
                            <th style="color: white ; background-color: #3b5998">EZENTIS</th>
                                         
                                      
                            <th style="color: white ; background-color: #3b5998">QUANTA</th>                  
                            <th style="color: white ; background-color: #3b5998">CAMPERU</th>                  
                                                                                        
                        </tr>
                    </thead>                    
                    <tbody>';

        foreach($data as $row){
            $style = null; 
            list($class, $btn) = ($row['idCuotaAgenda'] == null) ? array('btn btn-danger', null) : array('btn btn-success', '<button class="btn btn-info" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" onclick="editarCuotasAgendamiento($(this));">Editar Cuotas</button>');

            if($row['cobra'] == 1) {
                $row['cobra'] = '<div class="form-group">
                                    <button class="'.$class.'" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" 
                                        onclick="openModalCuotasAgenda($(this));">Registrar Cuotas</button>
                                 </div>
                                 <div>
                                    '.$btn.'
                                 </div>';

            } else if($row['lari'] == 1) {
                $row['lari'] = '<div class="form-group">
                                  <button class="'.$class.'" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" 
                                    onclick="openModalCuotasAgenda($(this));">Registrar Cuotas</button>
                                </div>
                                <div>
                                    '.$btn.'
                                </div>';
            } else if($row['dominion'] == 1) {
                $row['dominion'] = '<div class="form-group">
                                        <button class="'.$class.'" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" 
                                         onclick="openModalCuotasAgenda($(this));">Registrar Cuotas</button>
                                    </div>     
                                    <div>
                                         '.$btn.'
                                     </div>';
            } else if($row['ezentis'] == 1) {
                $row['ezentis'] = ' <div class="form-group">
                                      <button class="'.$class.'" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" 
                                       onclick="openModalCuotasAgenda($(this));">Registrar Cuotas</button>
                                    </div>
                                    <div>
                                        '.$btn.'
                                    </div>';
            } else if($row['quanta'] == 1) {
                $row['quanta'] = '<div class="form-group">
                                        <button class="'.$class.'" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" 
                                        onclick="openModalCuotasAgenda($(this));">Registrar Cuotas</button>
                                    </div>
                                    <div>
                                        '.$btn.'
                                    </div>';
            } else if($row['camperu'] == 1) {
                $row['camperu'] = '<div class="form-group">
                                    <button class="'.$class.'" data-jefatura="'.$row['jefatura'].'" data-id_empresacolab="'.$row['idEmpresaColab'].'" 
                                      onclick="openModalCuotasAgenda($(this));">Registrar Cuotas</button>
                                    </div>
                                    <div>
                                    '.$btn.'
                                    </div>';
            } 

            $html .='   <tr>
                            <td style="color: white ; background-color: #3b5998">'.$row['jefatura'].'</td>
                            <td>'.$row['cobra'].'</td>
                            <td>'.$row['lari'].'</td>							
                            <th>'.$row['dominion'].'</th>
                            <th>'.$row['ezentis'].'</th>		
                    	
                           					                        
                            <th>'.$row['quanta'].'</th>					                        
                            <th>'.$row['camperu'].'</th>					                        
                         			                                                    				                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function registrarCuotas() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $jefatura       = $this->input->post('jefatura');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idBandaHoraria = $this->input->post('idBandaHoraria');
            $cantidad       = $this->input->post('cantidad');

            //$countAgendamiento = $this->m_matriz_agendamiento->countCuotasAgenda($idEmpresaColab, $jefatura);

            // if($countAgendamiento > 0) {
            //     $arrayData = array(
            //         'cantidad'       => $cantidad,
            //         'idBandaHoraria' => $idBandaHoraria
            //     );
            //     $data = $this->m_matriz_agendamiento->updateAgendamiento($arrayData, $idEmpresaColab, $jefatura);
            // } else {
                $arrayData = array(
                    'jefatura'       => $jefatura,
                    'idEmpresaColab' => $idEmpresaColab,
                    'cantidad'       => $cantidad,
                    'idBandaHoraria' => $idBandaHoraria
                );
                $data = $this->m_matriz_agendamiento->insertAgendamiento($arrayData);
            // }

            $data['tablaMatriz'] = $this->tablaMatriz();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getElementModalCuotas() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $jefatura       = $this->input->post('jefatura');
            
            if($idEmpresaColab == null || $jefatura == null) {
                throw new Exception('error');
            }

            $data['cmbBandaHoraria'] = $this->m_matriz_agendamiento->getBandaHoraria($idEmpresaColab, $jefatura);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function openModalEditarCuotas() {
        $html = null;

        $idEmpresaColab = $this->input->post('idEmpresaColab');
        $jefatura       = $this->input->post('jefatura');        

        $dataArray = $this->m_matriz_agendamiento->getCuotasAgenda(NULL, $jefatura, $idEmpresaColab);
        $arrayBandaHoraria = $this->m_matriz_agendamiento->getBandaHoraria(NULL, NULL);
        
        $val = 0;
        $arrayEdit = array();
        foreach($dataArray as $row) {
            $jsonData['idCuotaAgenda']  = $row->idCuotaAgenda;
            $jsonData['jefatura']       = $row->jefatura;
            $jsonData['idEmpresaColab'] = $row->idEmpresaColab;
            $val++;
            $html .= '<div class="form-group col-md-6">
                        <label>Banda Horaria</label>
                        <select id="cmbBandaHoraria_'.$val.'" class="form-control" data-id_cuota_agenda="'.$row->idCuotaAgenda.'" data-val="'.$val.'" onchange="getDataEditar($(this))">
                            <option value="">Seleccionar Banda Horaria</option>';
                            foreach($arrayBandaHoraria as $row1) {
                                $selected = ($row->idBandaHoraria == $row1->idBandaHoraria) ? 'selected' : null;
                                $html .= '<option value="'.$row1->idBandaHoraria.'" '.$selected.'>'.$row1->horaInFin.'</option>';
                            }    
            $html .= '</select>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Cantidad</label>
                        <input id="cantidad_'.$val.'" data-id_cuota_agenda="'.$row->idCuotaAgenda.'" data-val="'.$val.'" type="number" value="'.$row->cantidad.'" class="form-control" onkeyup="getDataEditar($(this))"/>
                      </div>';
            array_push($arrayEdit, $jsonData);          
        }
        $data['arrayJsonEdit'] = $arrayEdit;
        $data['htmlElementEdit'] = $html;
        echo json_encode($data);
    }

    function actualizarCuotas() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $arrayData = $this->input->post('arrayData');
            
            $data = $this->m_matriz_agendamiento->actualizarCuotas($arrayData);
            $data['tablaMatriz'] = $this->tablaMatriz();
        } catch(Exception $e) {
            $e->getMessage();
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