<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_siom extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_siom');
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
               $data['tablaSiom']     = $this->getTablaSiom(null,null,null,null,null,null);             
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_BANDEJA_SIOM);
               $result = $this->lib_utils->getHTMLPermisos($permisos, 237, ID_PERMISO_HIJO_BANDEJA_SIOM, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_liquidacion/v_bandeja_siom',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaSiom($idSubProyecto, $jefatura, $idEmpresaColab, $noEnviado, $itemplan) {
        if($idSubProyecto == null && $jefatura == null && $idEmpresaColab == null && $noEnviado == null && $itemplan==null ){
            $data = null;
        }else{
            $data = $this->m_bandeja_siom->getBandejaSiom($idSubProyecto, $idEmpresaColab, $jefatura, $noEnviado, $itemplan);
            
        }
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
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
            if($data != null){                                                                                                                             
                foreach($data as $row){                               
                $html .=' <tr>
                            <th style="width:7%">'.(($row->codigoSiom == null && $row->ultimo_estado != 'ESTACION NO EJECUTADA') ? '<i title="Reenviar Trama" class="zmdi zmdi-hc-2x zmdi-replay" style="cursor:pointer" data-id_siom="'.$row->id_siom_obra.'" data-itemplan="'.$row->itemplan.'" data-ptr="'.$row->ptr.'" data-id_estacion="'.$row->idEstacion.'" data-estacion_desc="'.$row->estacionDesc.'" onclick="openModalReenviarTrama($(this));"></i>' :                                 
                                (($row->ultimo_estado != 'ESTACION NO EJECUTADA') ? '<a data-itemplan = "'.$row->itemplan.'" data-id_estacion = "'.$row->idEstacion.'" data-id_siom="'.$row->id_siom_obra.'" onclick="nuevoEnvioSiom(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-plus-circle-o"></i></a>
                                <a data-id_siom="'.$row->id_siom_obra.'" onclick="mostrarLogSiom(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i></a>' : '')).'                              
                            </th>
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
			 }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function asignarCodigoSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $codigoSiom = $this->input->post('codigoSiom');
            $remedy     = $this->input->post('remedy');
            
            $arrayData = array( 'codigoSiom'      => str_replace(' ', '', $codigoSiom),
                                'idUsuarioCodigo' => $this->session->userdata('idPersonaSession'),
                                'fechaCodigo'     => $this->fechaActual(),
                                'remedy'          => $remedy);
                    
            if($itemplan == null || $idEstacion == null) {
                throw new Exception('ND');
            }

            if($codigoSiom == null) {
                throw new Exception('Ingresar codigo siom');
            }

            $data = $this->m_bandeja_siom->ingresarCodigoSiom($itemplan, $idEstacion, $arrayData);
            $data['tablaBandejaSiom'] = $this->getTablaSiom(null,null,null,null,null,null);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = ($this->input->post('idEmpresaColab')=='') ? null : $this->input->post('idEmpresaColab');
            $idSubProyecto  = ($this->input->post('idSubProyecto')=='')  ? null : $this->input->post('idSubProyecto');
            $jefatura       = ($this->input->post('jefatura') == '')     ? null : $this->input->post('jefatura');
            $noEnviado      = ($this->input->post('noEnviado') == '')     ? null : $this->input->post('noEnviado');
            $itemplan       = ($this->input->post('itemplan') == '')     ? null : $this->input->post('itemplan');
            $data['tablaBandejaSiom'] = $this->getTablaSiom($idSubProyecto, $jefatura, $idEmpresaColab, $noEnviado, $itemplan);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
        function deleteRegistroSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');

            $data = $this->m_bandeja_siom->deleteRegistroSiom($itemplan, $idEstacion);
            $data['tablaBandejaSiom'] = $this->getTablaSiom(null,null,null,null,null,null);
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
    
    function getNodoAndEmplazamienos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id_siom_obra = $this->input->post('id_siom_obra');           
            $dataNodoItem = $this->m_bandeja_siom->getNodoByIdSiomObra($id_siom_obra);
            if($dataNodoItem==null){
                throw new Exception('Nodo No encontrado en el Itemplan.');
            }
            $listaNodoCandidatos = $this->m_bandeja_siom->listaNodosSiomPosibles();
            $html = '<option>&nbsp;</option>';
            foreach ($listaNodoCandidatos as $row) {
                $html .= '<option value="' . $row->idCentral . '">' . $row->codigo . ' - '.$row->tipoCentralDesc.'</option>';
            }
            $data['listaNodos']         =   $html;
            $data['idCentralObra']      =   $dataNodoItem['idCentral'];
            $data['codigoCentralObra']  =   $dataNodoItem['codigo'];
            $data['idSiomObra']         =   $id_siom_obra;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
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
    
    function getTablaConsultaSiom($listaEstado, $dataSiom, $tipo) {      
        
        $html = '<table id="data-table" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th>ESTADO</th>
                                <th>FECHA</th>
                                <th>USUARIO</th>
                            </tr>
                        </thead>
    
                        <tbody>';
        
           if($tipo == 1){
            $html .=' <tr>
                            <td>'.$dataSiom['ultimo_estado'].'</td>
                            <td>'.$dataSiom['fecha_ultimo_estado'].'</td>
                            <td>'.$dataSiom['nomCompleto'].'</td>
                         </tr>';
           }else if($tipo == 2){
               foreach($listaEstado as $row){
                 $html .='<tr>
                            <td>'.$row->estado_desc.'</td>
                            <td>'.$row->fechaRegistro.'</td>
                            <td>'.$row->usuario_registro.'</td>
                          </tr>';                   
               }
           }
       
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }
    
    
    function getEstacionesToSendSiom(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $dataNodoItem = $this->m_bandeja_siom->getEstacionesToSendSiom($itemplan);          
            $html = '<option>&nbsp;</option>';
            foreach ($dataNodoItem as $row) {
                if($row->porcentaje != 100){
                    $html .= '<option value="' . $row->idEstacion . '">' . $row->estacionDesc . '</option>';
                }
            }
            $data['listaEstacion']         =   $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
    
}