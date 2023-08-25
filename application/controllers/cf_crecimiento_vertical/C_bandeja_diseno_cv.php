<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_diseno_cv extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_diseno_cv');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
            $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
            $data['listaEECC']      = $this->m_utils->getAllEECC();
            $data['tablaAnalisisEconomico'] = $this->getTablaDisenoCv('','');
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, 183);
			$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, 183, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                    $this->load->view('vf_crecimiento_vertical/v_bandeja_diseno_cv',$data);
            }else{
                redirect('login','refresh');
            }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaAnalisisEconByItemplan() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan   = $this->input->post('itemplan');

            if($itemplan == null) {
                throw new Exception('ND');
            }

            $data['tablaAnalisisEconomico'] = $this->getTablaAnalisisEconomico($itemplan);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaDisenoCv($idJefatura, $idEmpresaColab) {
        $data = $this->m_bandeja_diseno_cv->getBandejaDisenoCv($idJefatura, $idEmpresaColab);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>ITEMPLAN</th>
                            <th>NOMBRE PROYECTO</th>
                            <th>SUBPROYECTO</th>
                            <th>JEFATURA</th>
                            <th>EECC</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    if($row['flg_orden_compra'] == 1) {
                        $btnModalArchivo = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi zmdi-file-plus" data-itemplan="'.$row['itemplan'].'" title="Revertir" onclick="openModalArchivo($(this))"></i>';
                    } else {
                        $btnModalArchivo = null;
                    }
                              
                    $html .=' <tr>
                                <td>'.$btnModalArchivo.'</td>
                                <td>'.$row['itemplan'].'</td>
                                <td>'.utf8_decode($row['nombreProyecto']).'</td>
                                <td>'.utf8_decode($row['subProyectoDesc']).'</td>
                                <td>'.utf8_decode($row['jefatura']).'</td>
                                <td>'.utf8_decode($row['empresaColabDesc']).'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function ingresarArchivosDisenoCv() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            
            if($this->session->userdata('idPersonaSession') == '' || $this->session->userdata('idPersonaSession') == null) {
                throw new Exception('Se cerro la sesi&oacute;n, cargue nuevamente la p&aacute;gina.');
            }
            
            $this->db->trans_begin();
            
            $itemplan = $this->input->post('itemplan');
            $comentario = $this->input->post('comentario');

            $uploaddir =  'uploads/cv_diseno/'.$itemplan.'/';//ruta final del file Tss
            $uploadfileTss = $uploaddir . basename($_FILES['fileTss']['name']);
            $uploadfileExp = $uploaddir . basename($_FILES['fileExped']['name']);
            
            
            $arrayLog =  array (  'tabla'          => 'planobra',
                        		  'actividad'      => 'actualizar Integral',
                        		  'itemplan'       => $itemplan,
                                  'fecha_registro' => $this->fechaActual(),
                                  'id_usuario'     => $this->session->userdata('idPersonaSession'),
                                  'idEstadoPlan'   => 3);
            if (!is_dir ( $uploaddir))
                mkdir ( $uploaddir, 0777 );
            
			#czavalacas 06.01.2019 agregue usu_upd y fecha_upd para el log del itemplan.
            if (move_uploaded_file($_FILES['fileTss']['tmp_name'], $uploadfileTss) && move_uploaded_file($_FILES['fileExped']['tmp_name'], $uploadfileExp)) {
                $arrayData = array('ubic_tss_cv'   => $uploadfileTss,
                                   'ubic_exped_cv' => $uploadfileExp,
                                   'comentario_cv' => $comentario,
                                   'idEstadoPlan'  => 3,
								   'usu_upd' 	   => $this->session->userdata('idPersonaSession'),
								   'fecha_upd' 	   => $this->fechaActual());
                $data = $this->m_bandeja_diseno_cv->updateData($arrayData, $itemplan, $arrayLog);
            }else {
               throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
            
            if($data['error'] == EXIT_ERROR) {
                throw new Exception('Hubo un problema, comuniquese con el administrador.');   
            } else {
                $arraLicencias = array();
                $listaEstacionAnclas = $this->m_utils->getEstacionesAnclasByItemplan($itemplan);
                foreach($listaEstacionAnclas as $row){
                    $dataLicencias = array(
                        'idEntidad'         => 2,
                        'idEstacion'        => $row->idEstacion,
                        'itemPlan'          => $itemplan,
                        'id_usuario_reg'    => $this->session->userdata('idPersonaSession'),
                        'fecha_registro'    => $this->fechaActual(),
                        'flg_validado'      => 0
                    );
                
                    array_push($arraLicencias, $dataLicencias);
                }
                
                $data = $this->m_utils->insertLicenciasFromCV($arraLicencias);
    
                if($data['error']==EXIT_ERROR) {
                    throw new Exception('Error al generar Licencias, no se asignaron las estaciones FO o COAXIAL al subproyecto.');
                }else {
                    $this->db->trans_commit();
                }
            }

        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaBandejaDisenoCv(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;

        try{ 
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');

            $idEmpresaColab = ($idEmpresaColab == '') ? NULL : $idEmpresaColab;
            $idJefatura     = ($idJefatura == '')     ? NULL : $idJefatura;
            
            $data['tablaBandeja'] = $this->getTablaDisenoCv($idJefatura, $idEmpresaColab);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
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