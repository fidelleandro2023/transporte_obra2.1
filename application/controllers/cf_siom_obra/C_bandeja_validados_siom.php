<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_validados_siom extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_siom_obra/m_bandeja_validados_siom');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC']     = $this->m_utils->getAllEECC();
               $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	   $data['listafase']     = $this->m_utils->getAllFase();
			   $data['listaMotivoObs']     = $this->m_utils->getMotivosObserValidados();
               $data['tablaSiom']     = $this->getTablaSiom(null);               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_BANDEJA_VALIDADOS_SIOM);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_INTEGRAL, ID_PERMISO_HIJO_BANDEJA_VALIDADOS_SIOM, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_siom_obra/v_bandeja_validados_siom',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaSiom($data) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10%"></th>
                            <th>Situacion</th> 
                            <th>Itemplan</th>   
                            <th>Indicador</th>                         
                            <th>SubProyecto</th>
                            <th>Jefatura</th> 
                            <th>EECC</th>  
                            <th>Estacion</th>   
                            <th>Codigo Siom</th> 
                            <th>Ultimo Estado</th>
                            <th>Fec. Ultimo Estado</th>
                        </tr>
                    </thead>                    
                    <tbody>';
               if($data!=null){                                                                                                         
                    foreach($data as $row){
						$btnExpediente = '';
                        if($row->path_expediente_diseno!= null){                            
                            $btnExpediente = '<a style="margin-left: 4px;" href="'.utf8_decode($row->path_expediente_diseno).'" download=""><i title="Descargar Expediente" class="zmdi zmdi-hc-2x zmdi-case-download"></i></a>';
                        }
                        $btnObser = '';
                        if($row->estado_observado   ==  1){//si esta observado..
                            $btnObser   =   '<a data-id_siom="'.$row->codigoSiom.'" onclick="modalDesObservar(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye-off"></i></a> ';
                        }else{//si no esta observado..
                            $btnObser   =   '<a data-id_siom="'.$row->codigoSiom.'" onclick="modalObservar(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a>';
                        }
                        
                        
                        $html .=' <tr>     
                                    <th style="width:7%"><a data-id_siom="'.$row->codigoSiom.'" onclick="mostrarLogSiom(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i></a>
                                        '.$btnObser.$btnExpediente.'                 
                                    </th>            
                                    <td>'.$row->estadoObser.'</td>         
                                    <td>'.$row->itemplan.'</td>
                                    <td>'.$row->indicador.'</td>
                                    <td>'.$row->subProyectoDesc.'</td>
                                    <td>'.$row->jefatura.'</td>
                                    <td>'.$row->empresaColabDesc.'</td>
                                    <td>'.$row->estacionDesc.'</td>     
                                    <td>'.$row->codigoSiom.'</td>
                                    <th>'.$row->ultimo_estado.'</th>
                                    <th>'.$row->fecha_ultimo_estado.'</th>
                                </tr>';
                    }
               }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function filtrarTablaSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = ($this->input->post('idEmpresaColab')=='') ? null : $this->input->post('idEmpresaColab');
            $idSubProyecto  = ($this->input->post('idSubProyecto')=='')  ? null : $this->input->post('idSubProyecto');
            $jefatura       = ($this->input->post('jefatura') == '')     ? null : $this->input->post('jefatura');
            $validado       = ($this->input->post('validado') == '')     ? null : $this->input->post('validado'); 
            $sirope         = ($this->input->post('sirope') == '')       ? null : $this->input->post('sirope');
            $situacion      = ($this->input->post('situacion') == '')    ? null : $this->input->post('situacion');
            $data['tablaBandejaSiom'] = $this->getTablaSiom($this->m_bandeja_validados_siom->getBandejaSiomValidados($idSubProyecto, $idEmpresaColab, $jefatura, $validado, $sirope, $situacion));
            $data['error'] = EXIT_SUCCESS;
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
    
    function saveObservacionSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                $idMotivo       = ($this->input->post('selectMotivo')=='') ? null : $this->input->post('selectMotivo');
                $comentario     = ($this->input->post('idComentario')=='')  ? null : $this->input->post('idComentario');
                $codigoSiom     = ($this->input->post('codigoSiom') == '')     ? null : $this->input->post('codigoSiom');
                
                $dataSiomObservado = array( 'codigo_siom'   =>  $codigoSiom,
                                            'idMotivoObservado' => $idMotivo,
                                            'comentario'    =>  $comentario,
                                            'fecha_registro'    => $this->fechaActual(),
                                            'usuario_registro'  =>  $this->session->userdata('idPersonaSession'),
                                            'tipo'  =>  1);//1 = observado
                
                $dataSiomUpdate = array( 'estado_observado'   =>  1);
                $data = $this->m_bandeja_validados_siom->saveObservacionSiom($dataSiomObservado, $codigoSiom, $dataSiomUpdate);
                //aqui guardar los datos..
                if($data['error'] == EXIT_SUCCESS){                
                    $idEmpresaColab = ($this->input->post('idEmpresaColab')=='') ? null : $this->input->post('idEmpresaColab');
                    $idSubProyecto  = ($this->input->post('idSubProyecto')=='')  ? null : $this->input->post('idSubProyecto');
                    $jefatura       = ($this->input->post('jefatura') == '')     ? null : $this->input->post('jefatura');
                    $validado       = ($this->input->post('validado') == '')     ? null : $this->input->post('validado');
                    $sirope         = ($this->input->post('sirope') == '')     ? null : $this->input->post('sirope');
                    $situacion      = ($this->input->post('situacion') == '')    ? null : $this->input->post('situacion');
                    $data['tablaBandejaSiom'] = $this->getTablaSiom($this->m_bandeja_validados_siom->getBandejaSiomValidados($idSubProyecto, $idEmpresaColab, $jefatura, $validado, $sirope, $situacion));
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('Ocurrio un error, refresque la pagina y vuelva a intentarlo');
                }                
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveDesObservacionSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                //$idMotivo       = ($this->input->post('selectMotivo')=='') ? null : $this->input->post('selectMotivo');
                $comentario     = ($this->input->post('idComentarioDes')=='')  ? null : $this->input->post('idComentarioDes');
                $codigoSiom     = ($this->input->post('codigoSiom') == '')     ? null : $this->input->post('codigoSiom');
    
                $dataSiomObservado = array( 'codigo_siom'   =>  $codigoSiom,
                                            'comentario'    =>  $comentario,
                                            'fecha_registro'    => $this->fechaActual(),
                                            'usuario_registro'  =>  $this->session->userdata('idPersonaSession'),
                                            'tipo'  =>  2);//2= liberado
    
                $dataSiomUpdate = array( 'estado_observado'   =>  0);
                $data = $this->m_bandeja_validados_siom->saveObservacionSiom($dataSiomObservado, $codigoSiom, $dataSiomUpdate);
                //aqui guardar los datos..
                if($data['error'] == EXIT_SUCCESS){
                    $idEmpresaColab = ($this->input->post('idEmpresaColab')=='') ? null : $this->input->post('idEmpresaColab');
                    $idSubProyecto  = ($this->input->post('idSubProyecto')=='')  ? null : $this->input->post('idSubProyecto');
                    $jefatura       = ($this->input->post('jefatura') == '')     ? null : $this->input->post('jefatura');
                    $validado       = ($this->input->post('validado') == '')     ? null : $this->input->post('validado');
                    $sirope         = ($this->input->post('sirope') == '')     ? null : $this->input->post('sirope');
                    $situacion      = ($this->input->post('situacion') == '')    ? null : $this->input->post('situacion');                    
                    $data['tablaBandejaSiom'] = $this->getTablaSiom($this->m_bandeja_validados_siom->getBandejaSiomValidados($idSubProyecto, $idEmpresaColab, $jefatura, $validado, $sirope,$situacion));
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('Ocurrio un error, refresque la pagina y vuelva a intentarlo');
                }
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDataSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $codigo_siom = $this->input->post('id_siom');
    
            if($codigo_siom == null) {
                throw new Exception("ERROR idSiom No Valido");
            }

            $tablaSiom = $this->getTablaConsultaSiom($this->m_bandeja_validados_siom->listaLogEstadosSIom($codigo_siom));
            $data['error'] = EXIT_SUCCESS;
            $data['tablaSiom'] = $tablaSiom;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaConsultaSiom($listaEstado) {
    
        $html = '<table id="data-table" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th>TIPO</th>
                                <th>FECHA</th>
                                <th>USUARIO</th>
                                <th>MOTIVO</th>
                                <th>COMENTARIO</th>
                            </tr>
                        </thead>
    
                        <tbody>';
            if($listaEstado != null){
                   foreach($listaEstado as $row){
                     $html .='<tr>
                                <td>'.$row->tipoObservacion.'</td>
                                <td>'.$row->fecha_registro.'</td>
                                <td>'.$row->nombreCompleto.'</td>
                                <td>'.$row->descripcion.'</td>
                                <td>'.strtoupper($row->comentario).'</td>
                              </tr>';                   
                   }
            }
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }
    
}