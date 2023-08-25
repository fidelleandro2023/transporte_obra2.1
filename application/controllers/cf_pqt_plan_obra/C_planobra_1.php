<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_planobra extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_pqt_mantenimiento/m_pqt_central');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica');
        $this->load->model('mf_ejecucion/M_porcentaje');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                /*carga de la tabla */           
               //$data['listartabla'] = $this->makeHTMLTablaCentral($this->m_planobra->getAllPlanesObra());
                /*carga de proyecto*/
               /*********miguel rios 13062018***********/
               /***$data['listaProy'] = $this->m_utils->getAllProyecto();***/
                $data['listaProy'] = $this->m_utils->getAllProyectoExcepcion();
               /****************************************/
               $data['listaTiCen'] = $this->m_pqt_central->getPqtAllCentral();
              /*carga de empresas electricas*/
               $data['listaeelec'] = $this->m_utils->getAllEELEC();
               /*carga de fase*/
               $data['listafase'] = $this->m_utils->getAllFase();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
               // permiso para registro individual modificar
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PQT_PLAN_DE_OBRA, ID_PERMISO_HIJO_PQT_REGIND_OBRA, ID_MODULO_PAQUETIZADO);
        	   
               $data['opciones'] = $result['html'];
        	   $this->load->view('vf_pqt_plan_obra/v_registro_individual',$data);
        	   
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLTablaCentral($listartabla){
     
        $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>Nombre Plan</th>
                            <th>Subproyecto</th>
                            <th>Central</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>fecha Inicio</th>
                            <th>fecha PrevEjecucion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';
		   																			                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->ItemPlan.'</td> 
                            <td>'.$row->Nombre.'</td> 
                            <td>'.$row->Subproyecto.'</td>                                 
                            <td>'.$row->Central.'</td>
                            <td>'.$row->Zonal.'</td>
                            <td>'.$row->EmpresaColab.'</td>
                            <td>'.$row->fechaInicio.'</td>
                            <td>'.$row->fechaPreviaEjecucion.'</td>
                            <td>'.$row->Estado.'</td>      
                                               
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    /**
     * ultima modificacion 20.06.2019 czavalacas
     * @throws Exception
     */
     public function createPlanobra(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProy         = $this->input->post('selectProy');
            $idSubproy      = $this->input->post('selectSubproy');
            $idCentral      = $this->input->post('selectCentral');
            $idzonal        = $this->input->post('selectZonal');
            $eecc           = $this->input->post('selectEmpresaColab');
            $eelec          = $this->input->post('selectEmpresaEle');           
            $fase           = $this->input->post('selectFase') ? $this->input->post('selectFase') : null;
            $indicador      = $this->input->post('inputIndicador');
            //$cantidadTroba  = $this->input->post('inputCantObra');
            $cantidadTroba  =   '0';
            $fechaInicio    = $this->input->post('inputFechaInicio');
            $nombreplan     = $this->input->post('inputNombrePlan');
            //$uip            = $this->input->post('inputUIP');
            $uip            =   '0';
            $cordx          = $this->input->post('inputCoordX');
            $cordy          = $this->input->post('inputCoordY');
            $has_coti       = $this->input->post('selectCotizacion');
            $itemMadre      = $this->input->post('inputItemMadre');
            
            $idTipoFactorMedicion = $this->input->post('hfIdFactorMedicion');
            $cantFactorMedicion = $this->input->post('inputCantidadFactorMedicion');
            
            log_message('error','$eecc '.$eecc);
            
            if($fase == null){
                throw new Exception('Debe seleccionar una fase!! '.$fase);
            }
           /**VALIDAMOS SI CUENTA CON LA CONFIGURACION DE CREARSE EN OBRA 20.06.2019 czavala**/
           $hasAutoPlanEnObra = $this->m_utils->getIdEstadoPlanCambio($idSubproy);

           if($hasAutoPlanEnObra == null) {//SI NO TIENE LA CONFIGURACION SE TOMA EN CUENTA LA COTIZACION DE CASO CONTRARIO NO SE TOMA EN CUENTA COTIZACION
                if($has_coti    ==  '1'){//SI REQUIERE COTIZACION NACE EN PRE REGISTRO
                    $estadoplan     = ESTADO_PLAN_PRE_REGISTRO;
                    if($idzonal==''){
                        $idzonal = 0;
                    }                
                }else{//SI NO REQUERIE COTIZACION NACE EN PRE DISENO
                    $estadoplan     = ESTADO_PLAN_PRE_DISENO;
                }                
           }else{//SI TIENE CONFIGURACION DE NACER EN OBRA PRIMERO SE REGISTRA EN ESTADO PRE DISENO
               $estadoplan     = ESTADO_PLAN_PRE_DISENO;
           }
           
            $this->m_planobra->deleteLogImportPlanObraSub();
            $itemplan=$this->m_planobra->generarCodigoItemPlan($idProy,$idzonal);      
            $data = $this->m_planobra->insertarPlanobraPaquetizado($itemplan,$idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, $nombreplan,$indicador ,$uip, $cordx, $cordy ,$cantidadTroba, $has_coti, $itemMadre,null,null,null,null,null,null,null,null,null,$idTipoFactorMedicion, $cantFactorMedicion);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar planobra');
            }else{
                $itemplanData= $this->m_planobra->obtenerUltimoRegistro();
                
                $data2 =$this->m_planobra->insertarLogPlanObra($itemplanData,$this->session->userdata('idPersonaSession'), ID_TIPO_PLANTA_EXTERNA);
                if($data2['error']==EXIT_ERROR){
                    throw new Exception('Error al Insertar en el log de planobra');
                }
                $data['itemplannuevo']  =   $itemplanData;
                
                if($hasAutoPlanEnObra == null) {//SI NO TIENE AUTO REGISTRO EN OBRA TOMAR EN CUENTA LA COTIZACION DE CASO CONTRARIO NO TOMAR EN CUENTA
                    if($has_coti    ==  '1'){//SI TIENE COTIZACION
                        $uploaddir =  'uploads/cotizacion/'.$itemplanData.'/';//ruta final del file
                        $uploadfile = $uploaddir . basename($_FILES['file']['name']);
                        if (! is_dir ( $uploaddir))
                            mkdir ( $uploaddir, 0777 );
                        
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                            $succ = $this->m_planobra->saveFileCotizacionInit($itemplanData, $uploadfile);
                        }else {
                            throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                        }
                    }
                }
                //INSERT DETALLE OBRAS PUBLICAS
                
                if($idProy  ==  ID_PROYECTO_OBRA_PUBLICA){
                    
                    
                    $departamento   = $this->input->post('txt_departamento');
                    $provincia      = $this->input->post('txt_provincia');
                    $distrito       = $this->input->post('txt_distrito');
                    $fec_recepcion  = $this->input->post('fecRecepcionOP');
                    $nomCliente     = $this->input->post('inputNomCli');
                    $numCarta       = $this->input->post('inputNumCar');
                    $ano            = $this->input->post('selectAno');
                    $numCartaFin    = $this->input->post('numCartaFin');
                    $kickOff        = $this->input->post('selectKickOff');
                    
                    $uploaddir =  'uploads/obra_publica/'.$itemplanData.'/';//ruta final del file
                    $uploadfile = $uploaddir . basename($_FILES['fileOP']['name']);
                    if (! is_dir ( $uploaddir))
                        mkdir ( $uploaddir, 0777 );
                    
                    if (move_uploaded_file($_FILES['fileOP']['tmp_name'], $uploadfile)) {
                        $arrayDataOP = array(
                            'itemplan'              =>  $itemplanData,
                            'departamento'          =>  $departamento,
                            'provincia'             =>  $provincia,
                            'distrito'              =>  $distrito,
                            'fecha_recepcion'       =>  $this->fechaActual(),
                            'nombre_cliente'        =>  $nomCliente,
                            'numero_carta'          =>  $numCarta,
                            'ano'                   =>  $ano,
                            'numero_carta_pedido'   =>  $numCartaFin,
                            'ruta_carta_pdf'        =>  $uploadfile,
                            'usuario_envio_carta'   =>  $this->session->userdata('idPersonaSession'),
                            'has_kickoff'           =>  $kickOff,
                            'estado_kickoff'        =>  (($kickOff  ==  1)  ?   'PENDIENTE' : null)
                            );
                        $this->m_planobra->saveDetalleObraPublica($arrayDataOP);                        
                        
                    }else {
                        throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                    }
                                        
                }
                if($hasAutoPlanEnObra == null) {//SI NO TIENE CONFIGURACION AUTO CREACION EN OBRA PREGUNTA SI TIENE ADJUDICACION AUTOMATICA EN CASO CONTRARIO NO SE ADJUDICARA DICHA OBRA.
                    if($has_coti    !=  '1'){//SOLO SI NO TIENE ADJUDICACION ES DECIR NACE EN PRE DISENO PREGUNTAR LA AUTO ADJUDICACION
                        /////////////////////////////AUTO ADJUDICAR ITEMPLAN
                        $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplanData);
                        $this->session->set_userdata('has_fo', $conEsta['fo']);
                        $this->session->set_userdata('has_coax', $conEsta['coaxial']);
                        $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy);
                        if($dias    !=  null){//SOLO SI TIENE DIAS LO ADJUDICAMOS
                            $curHour = date('H');
                            if($curHour >= 13){//13:00 PM
                                $dias = ($dias + 1);
                            }
                            $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
                            $idFechaPreAtencionFo= date ( 'Y-m-j' , $nuevafecha );
                            $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplanData, $idSubproy, $idCentral, $eecc, $idFechaPreAtencionFo, $idFechaPreAtencionFo);
                        }
                        ///////////////////////////////////////////////////////////////
                    }         
                }else{//SI TIENE CONFIGURACION DE CREACION EN OBRA SE CAMBIA EL ESTADO A EN OBRA
                    $datoEnObra =   $this->m_planobra->changeEstadoEnObraWithLog($itemplanData);                   
                }                
            }          
    
        }catch(Exception $e){
            log_message('error','Error '.$e->getMessage());
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*ENLAZA CON VIEW PARA ENVIAR A MODEL Y recibir DATOS DEL ITEMPLAN PARA LA EDICION*/
  
    public function getHTMLChoiceSubProy(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProyecto = $this->input->post('proyecto');
            $listaSubProy = $this->m_utils->getSubProyByProyRegIPSoloPqt($idProyecto, 1);
            $html = '<option value="">&nbsp;</option>';           
            foreach($listaSubProy->result() as $row){
                $html .= '<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     public function getFechaPreEjecuCalculo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        
        try{
            $fechaInicio = $this->input->post('fecha');
            $subproy = $this->input->post('subproyecto');
          
            $fechaCalculado = $this->m_utils->getCalculoTiempoSubproyecto($fechaInicio,$subproy);
            
            $data['fechaCalculado'] = $fechaCalculado;
            $data['error']    = EXIT_SUCCESS;

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function getHTMLChoiceZonal(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $listaZonal = $this->m_utils->getZonalXPqtCentral($idCentral);
            $html = '';
            $idzonalselect='';
            foreach($listaZonal->result() as $row){
                $html .= '<option value="'.$row->idzonal.'">'.$row->zonalDesc.'</option>';
                $idzonalselect=$row->idzonal;
            }
            $data['listaZonal'] = $html;
            $data['idZonalSelec'] = $idzonalselect;
            
            $html = '';
            $listaEECC = $this->m_utils->getEECCXPqtCentral($idCentral);            
            $idEECCselect='';
            foreach($listaEECC->result() as $row){
                $html .= '<option value="'.$row->idEmpresaColab.'">'.$row->empresaColabDesc.'</option>';
                $idEECCselect=$row->idEmpresaColab;
            }
            $data['listaEECC'] = $html;
            $data['idEECCSelec'] = $idEECCselect;
            
            $listaJefatura = $this->m_utils->getJefaturaXPqtCentral($idCentral);
            $jefatura='';
            foreach($listaJefatura->result() as $row){
                $jefatura=$row->jefatura;
            }
            $data['jefatura'] = $jefatura;
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
/*
    public function getHTMLChoiceEECC(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $listaEECC = $this->m_utils->getEECCXCentral($idCentral);
            $html = '';
            $idEECCselect='';
            foreach($listaEECC->result() as $row){
                $html .= '<option value="'.$row->idEmpresaColab.'">'.$row->empresaColabDesc.'</option>';
                 $idEECCselect=$row->idEmpresaColab;
            }
            $data['listaEECC'] = $html;
            $data['idEECCSelec'] = $idEECCselect;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
*/


    function createPlanObraFromSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;       
        try{
            header('Access-Control-Allow-Origin: *');
            /***DATOS FROM SISEGOS***/
            $id          = $this->input->post('id');
            $indicador   = $this->input->post('sisego');
            $pep         = $this->input->post('pep');
            $fecha_envio = $this->input->post('envio');
            $mdf         = $this->input->post('mdf');
            $segmento    = $this->input->post('segmento');
            $cliente     = $this->input->post('cliente');
            $eecc        = $this->input->post('eecc');
            $jefatura    = $this->input->post('jefatura');
            $region      = $this->input->post('region');
            $dias        = $this->input->post('dias');
            $tipo_cliente = utf8_decode($this->input->post('tipo_cliente'));
            $tipo_diseno        = utf8_decode($this->input->post('tipo_diseno'));
            $tipo_requerimiento = utf8_decode($this->input->post('tipo_requerimiento'));
            $nombre_estudio     = utf8_decode($this->input->post('nombre_estudio'));
            $duracion           = utf8_decode($this->input->post('duracion'));
            $acceso_cliente     = utf8_decode($this->input->post('acceso_cliente'));
            $tendido_externo    = utf8_decode($this->input->post('tendido_externo'));
            $tipo_sede    = utf8_decode($this->input->post('tipo_sede'));
            $per          = utf8_decode($this->input->post('per'));
            
            $coordenada_x       = $this->input->post('latitud');
            $coordenada_y       = $this->input->post('longitud');
            
            $dataArray = array( 
                                'tipo_diseno'        => utf8_decode($tipo_diseno),
                                'nombre_estudio'     => utf8_decode($nombre_estudio),
                                'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
                                'duracion'           => $duracion,
                                'acceso_cliente'     => utf8_decode($acceso_cliente),
                                'tendido_externo'    => utf8_decode($tendido_externo),
                                'tipo_sede'          => utf8_decode($tipo_sede)
                            );
            
            /***DATOS COMPLMENTARIOS ITEMPLAN***/
            $idProy     = '3';//ID PROYECTO SISEGOS = 3
            $idSubproy  =   $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));
            if($idSubproy == null){
                throw new Exception('segmento no reconocido.');
            }
                        
            $dataCentral = $this->m_utils->getIdPqtCentralByPqtCentralDesc($mdf);
            if($dataCentral == null){
                throw new Exception('MDF no registrado.');
            }
            
            $existSisego = $this->m_utils->existeSisego($indicador);
            if($existSisego['count'] >= 1){
                $data['itemplan']   =   $existSisego['itemplan'];
                throw new Exception('SISEGO ya se encuentra registrado.');
            }
            
            $idCentral      = $dataCentral['idCentral'];
            $idzonal        = $dataCentral['idZonal'];            
            $eecc           = $dataCentral['idEmpresaColab'];             
            $eelec          = 6;            
            $estadoplan     = ESTADO_PLAN_PRE_DISENO;
            $fase           = ID_FASE_ANIO_CREATE_ITEMPLAN;//2019
            $cantidadTroba  = 0;
            $fechaInicio    = $fecha_envio;
            $nombreplan     = $indicador." - ".$cliente;
            $uip            = 0;
            $cordx          = $coordenada_x;
            $cordy          = $coordenada_y;
            $has_coti       = '0';//sisegos no requiere cotizacion
            
            
            $this->m_planobra->deleteLogImportPlanObraSub();               
            
            $itemplan   =   $this->m_planobra->generarCodigoItemPlan($idProy,$idzonal);
            
            $data = $this->m_planobra->insertarPlanobra($itemplan,$idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, 
                                                       $nombreplan,$indicador ,$uip, $cordx, $cordy ,$cantidadTroba, $has_coti, null,
                                                       $tipo_requerimiento,$tipo_diseno,$nombre_estudio,$duracion,$acceso_cliente,$tendido_externo, $tipo_sede, $tipo_cliente, $per);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('1) Error interno al registrar el itemplan.');
            }else{
                $itemplanData= $this->m_planobra->obtenerUltimoRegistro(); 
                log_message('error',$itemplanData);
                if($itemplanData!=null){
                    $this->m_planobra->insertarLogPlanObra($itemplanData,0,ID_TIPO_PLANTA_EXTERNA);//ID USUARIO = 0 FROM SISEGO              
                    $data['itemplan']   = $itemplanData;
                    $dataEmpresa = $this->m_utils->getEECCXPqtCentral($idCentral, 1);
                    $data['empresacolab'] = $dataEmpresa['empresaColabDesc'];
                    $data['msj'] = 'Registro Exitoso.';
                    $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplanData, '', $indicador, $eecc, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 1);
                }else if($itemplanData==null){
                    $data['itemplan']   = null;
                    $data['msj'] = 'Error al obtener el itemplan.';
                }               
                $data['id']         = $id;
            }        
           /////////////////////////////AUTO ADJUDICAR ITEMPLAN
            $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplanData);
            $this->session->set_userdata('has_fo', $conEsta['fo']);   
            $this->session->set_userdata('has_coax', $conEsta['coaxial']); 
            $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy);
            $curHour = date('H');
            if($curHour >= 13){//13:00 PM
                $dias = ($dias + 1);
            }
            $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
            $idFechaPreAtencionFo= date ( 'Y-m-j' , $nuevafecha );           
            $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplanData, $idSubproy, $idCentral, $eecc, null, $idFechaPreAtencionFo);
            /////////////////////////////////////////////////////////////// 
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 1);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /**METODO REGISTRO DETALLE SISEGO**/
    function getComboTipoObra() {
        $arrayFichaTecSisego = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(3);
        $arrayTipoFichaSisego = $this->m_bandeja_ficha_tecnica->getTipoTrabajoFichaTecnica();
        $arrayFicha = array();  
        foreach($arrayFichaTecSisego->result() as $row) {
            array_push($arrayFicha, $row);
        }

        $arrayTipoFicha = array();

        foreach($arrayTipoFichaSisego->result() as $row) {
            array_push($arrayTipoFicha, $row); 
        }
        $arrayDataTipoObra = $this->m_planobra->getComboTipoObra();
        $arrayCmbTipo = array();
        foreach($arrayDataTipoObra AS $row) {
            array_push($arrayCmbTipo, $row);
        }
        $data['cmbTipoObra']    = $arrayCmbTipo;
        $data['arrayFicha']     = $arrayFicha;
        $data['arrayTipoFicha'] = $arrayTipoFicha;
        echo json_encode($data); 
    }

    function getComboCodigo() {
        $arrayCmbCodigo=array();
        $jefatura = $this->input->post('jefatura');
        $arrayCodigo = $this->m_utils->getCodigoPqtCentral($jefatura);
        foreach($arrayCodigo AS $row) {
            array_push($arrayCmbCodigo, $row);
        }
        $data['cmbCodigo']   = $arrayCmbCodigo;
        echo json_encode($data); 
    }


    function saveSisegoPlanObra(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            /** FROM
             * 1 = 'bandeja pendiente de Ejecucion',
               2 = 'termino obra Sinfix'
             */
            $itemplan                   = $this->input->post('itemplan');
            $from                       = $this->input->post('from');
            $tipo_obra                  = $this->input->post('tipo_obra');
            $nap_nombre                 = $this->input->post('nap_nombre');
            $nap_num_troncal            = $this->input->post('nap_num_troncal');
            $nap_cant_hilos_habi        = $this->input->post('nap_cant_hilos_habi');
            $nap_nodo                   = $this->input->post('nap_nodo');
            $nap_coord_x                = $this->input->post('nap_coord_x');
            $nap_coord_y                = $this->input->post('nap_coord_y');
            $nap_ubicacion              = $this->input->post('nap_ubicacion');
            $nap_num_pisos              = $this->input->post('nap_num_pisos');
            $nap_zona                   = $this->input->post('nap_zona');
            $fo_oscu_cant_hilos         = $this->input->post('fo_oscu_cant_hilos');
            $fo_oscu_cant_nodos         = $this->input->post('fo_oscu_cant_nodos');
            $trasla_re_cable_externo    = $this->input->post('trasla_re_cable_externo');
            $trasla_re_cable_interno    = $this->input->post('trasla_re_cable_interno');
            $fo_tra_cant_hilos          = $this->input->post('fo_tra_cant_hilos');
            $fo_tra_cant_hilos_hab      = $this->input->post('fo_tra_cant_hilos_hab');
            $nap_idCmbUbicacion         = $this->input->post('nap_idCmbUbi');
            $licenciaAfirm              = $this->input->post('licenciaAfirm');
            $descEmpresaColab           = $this->input->post('descEmpresaColab');
            $indicador                  = $this->input->post('indicador');
            $jefatura                   = $this->input->post('jefatura');
            /**NODOS QUE PROVIENEN DEL FORMULARIO  "N"**/
            $listaNomNodos             = json_decode($this->input->post('nodos'), true);
            $idEstacion                = $this->input->post('idEstacion');
            $idEstadoPlan              = $this->input->post('idEstadoPlan');
            
            $pisoGlobal         = $this->input->post('pisoGlobal');
            $sala               = $this->input->post('sala');
            $nroODF             = $this->input->post('nroODF');
            $bandeja            = $this->input->post('bandeja');
            $nroHilo            = $this->input->post('nroHilo');
            //DATA FICHA TECNICA
            $arrayJson          = $this->input->post('arrayJsonData');
            $observacion        = $this->input->post('observacion');
            $idEstacion         = $this->input->post('idEstacion');
            $idFichaTecnicaBase = $this->input->post('idFichaTecnicaBase');
            
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                 throw new Exception('La sesi&oacute;n caduc&oacute;, recargue la p&aacute;gina nuevamente.');
            }

            if($licenciaAfirm == NULL) {
                throw new Exception('Confirmar si es con licencia o no.');
            }
 
            if($tipo_obra == NULL || $tipo_obra == 0) {
                throw new Exception('Seleccionar tipo de obra.');
            }
            $arrayNodos = array();
            if($tipo_obra == ID_TIPO_OBRA_CREACION_NAP) {
                if($nap_nombre == null || $nap_num_troncal==null || $nap_cant_hilos_habi==null || $nap_nodo ==null || $nap_coord_x == null || $nap_coord_y == null || $nap_idCmbUbicacion == 0) {
                    throw new Exception('Faltan ingresar datos');
                }
                if($nap_idCmbUbicacion == 3) {
                    if($nap_num_pisos == null) {
                        throw new Exception('No ingreso el n&uacute;mero de pisos');
                    }
                } else if($nap_idCmbUbicacion == 4) {
                    if($nap_zona == null) {
                        throw new Exception('No ingreso zona');
                    }
                }
            } 
            else if($tipo_obra == ID_TIPO_OBRA_FO_OSCURA) {
                if(count($listaNomNodos) == 0) {
                    throw new Exception('ingresar nombre de nodos');
                }
                if($fo_oscu_cant_hilos == null || $fo_oscu_cant_nodos==null) {
                    throw new Exception('Faltan ingresar datos');
                }        
            } else if($tipo_obra == ID_TIPO_OBRA_TRASLADO) {
                if($trasla_re_cable_externo == null || $trasla_re_cable_interno == null) {
                    throw new Exception('falta ingresar datos');
                }        
            } else if($tipo_obra == ID_TIPO_OBRA_FO_TRADICIONAL) {
                if($fo_tra_cant_hilos == null || $fo_tra_cant_hilos_hab == null) {
                    throw new Exception('falta ingresar datos');
                }        
            }
   
            foreach($listaNomNodos as $nodo) {
                $nodo_tmp = array();
                $nodo_tmp['itemplan']   = $itemplan;
                $nodo_tmp['origen']     = $from;
                $nodo_tmp['nodo']       = $nodo['value'];
                array_push($arrayNodos, $nodo_tmp);
            }

            $data = $this->m_planobra->saveSisegoPlanObra(  $itemplan, $from,  $tipo_obra, $nap_nombre,    $nap_num_troncal,   $nap_cant_hilos_habi,
                                                            $nap_nodo,  $nap_coord_x,   $nap_coord_y,   $nap_ubicacion, $nap_num_pisos, $nap_zona,
                                                            $fo_oscu_cant_hilos,   $fo_oscu_cant_nodos,    $trasla_re_cable_externo,
                                                            $trasla_re_cable_interno,   $fo_tra_cant_hilos, $fo_tra_cant_hilos_hab, $arrayNodos, $licenciaAfirm,
                                                            $pisoGlobal, $sala, $nroODF, $bandeja, $nroHilo);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('1) Error interno al registrar el SisegoPlanObra.');
            } else {
                $arrayDataLog = array(
                    'tabla'            => 'sinfix',
                    'actividad'        => 'Registro Formulario',
                    'itemplan'         => $itemplan,
                    'fecha_registro'   => $this->fechaActual(),
                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                 );
                $this->m_utils->registrarLogPlanObra($arrayDataLog);
                
                $countFichaTec = $this->m_utils->countFichaTecnica($itemplan);

                if($countFichaTec == 0) {
                    $this->registrarFichaSinfix($arrayJson, $itemplan, $observacion, $idEstacion, $idFichaTecnicaBase);
                }
                
                if($from == 2 && $idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                    $cant = $this->M_porcentaje->cantPorcentajeRegistroSisego($itemplan, $idEstacion);
                    if(isset($cant->porcentaje)) {
                        $porcentaje = ($cant->porcentaje >= 90) ? 100 :  $cant->porcentaje + 10;
                        // if($cant->porcentaje == 90) {
                           
                        if($porcentaje == 100) {
                            $this->M_porcentaje->updateEstadoPO($itemplan, $idEstacion);
                            $arrayData = array('idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
                                               'fechaPreLiquidacion' => $this->fechaActual(),
                                                'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
                                
                            $flgValid = $this->M_porcentaje->getValidaSubProyecto($itemplan, $idEstacion, $porcentaje);

                                if($flgValid->flg_focoaxial == 1 || $flgValid->flg_focoaxial == 2 || $flgValid->flg_focoaxial == 3 || $flgValid->flg_focoaxial == 4 || $flgValid->flg_acti_fo == 2) {
                                $flg = $this->M_porcentaje->updateEstadoPlanObra($itemplan, $arrayData);
                            } else {
                                $flg = null;
                            }
                            
                            $countTrama = $this->M_porcentaje->countTrama($itemplan, 'LIQUIDACION OBRA');
                            if($countTrama == 0 && $flgValid->flg_sisego == 1) {
                                $this->enviarTrama($itemplan, $indicador, 2, $jefatura ,$descEmpresaColab);
                            }
                            if($flg == 0) {
                                _log("FALLO AL ACTUALIZAR EL ESTADO");
                            } else if($flg == 1) {
                                $arrayDataLog = array(
                                    'tabla'            => 'planobra',
                                    'actividad'        => 'Obra Pre-Liquidada',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
                                 );
        
                                $this->m_utils->registrarLogPlanObra($arrayDataLog);
                            }
                        }
                            $arrayData = array(
                                'porcentaje'   => $porcentaje,
                                'fecha'        => $this->fechaActual(),
                             );
                             $this->M_porcentaje->updateItemPlanEstacionAvance($arrayData, $itemplan, $idEstacion);
                    } else {
                        $arrayData = array(
                                    'itemplan'     => $itemplan,
                                    'idEstacion'   => $idEstacion,
                                    'porcentaje'   => 10,
                                    'fecha'        => $this->fechaActual(),
                                 );
                        $this->M_porcentaje->insertItemPlanEstacionAvance($arrayData);
                    }
                    
                } 
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarFichaSinfix($arrayJson, $itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase) {
        $val = $this->registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson);

        if($val == 1) {
            $data['error'] = EXIT_SUCCESS;
            $arrayDataLog = array(
                'tabla'            => 'sinfix',
                'actividad'        => 'Registro Ficha',
                'itemplan'         => $itemPlan,
                'fecha_registro'   => $this->fechaActual(),
                'id_usuario'       => $this->session->userdata('idPersonaSession'),
                );

            $this->m_utils->registrarLogPlanObra($arrayDataLog);
        } else {
            $data['error'] = EXIT_ERROR;
        }
    }

    function registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson) {
        $idCuadrilla = $this->M_porcentaje->getCuadrillaOne($itemPlan, $idEstacion);
        $coordenada  = $this->M_porcentaje->getCoordenadas($itemPlan);
        $dataInsert = array(
                                'jefe_c_nombre'         => $idCuadrilla,
                                'observacion'           => $observacion,
                                'itemplan'              => $itemPlan,
                                'fecha_registro'        => date("Y-m-d H:m:s"),
                                'usuario_registro'      =>  $this->session->userdata('idPersonaSession'),
                                'coordenada_x'          => $coordenada['coordX'],
                                'coordenada_y'          => $coordenada['coordY'],
                                'flg_activo'            => '1',
                                'id_ficha_tecnica_base' => $idFichaTecnicaBase,
                                'id_estacion'           => $idEstacion
                            );

        $val = $this->M_porcentaje->isertFichaTecnica($dataInsert, $arrayJson);
        return $val;	
    }

    function enviarTrama($itemPlan, $indicador, $from, $jefatura ,$descEmpresaColab){
        // $data['error']    = EXIT_ERROR;
        // $data['msj']      = null;
        // $data['cabecera'] = null;
        // try{        
            // if($data['error'] == EXIT_ERROR){
            //     throw new Exception($data['msj']);
            // }           
            $dataSend = ['itemplan' => $itemPlan,
                         'fecha'    => $this->fechaActual(),
                         'sisego'   => $indicador];
            
            $url = 'https://gicsapps.com:8080/obras2/recibir_eje.php';
            
            $response = $this->m_utils->sendDataToURL($url, $dataSend);
            //if($response['error'] == EXIT_SUCCESS){
                    $this->m_utils->saveLogSigoplus('LIQUIDACION OBRA', null , $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 4);
            //}else{
               // $this->m_utils->saveLogSigoplus('SINFIX', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
            //}
        //$data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            
        // }catch(Exception $e){
        //     $data['msj'] = $e->getMessage();
        // }
        // echo json_encode(array_map('utf8_encode', $data));
    }
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
   function createClusteFromSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            header('Access-Control-Allow-Origin: *');
            /***DATOS FROM SISEGOS***/
            $id_trama    = $this->input->post('id');
            $hijos       = $this->input->post('hijos');
            $sisego      = $this->input->post('sisego');
            $segmento    = $this->input->post('segmento');
            $fecha_envio = $this->input->post('fecha_envio');
            $mdf         = $this->input->post('mdf');
            $coordX      = $this->input->post('longitud');
            $coordY      = $this->input->post('latitud');
            $cliente     = $this->input->post('cliente');
            $idSubproy  =   $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));            
            if($idSubproy == null){
                throw new Exception('segmento no reconocido.');
            }
            
            $dataCentral = $this->m_utils->getIdPqtCentralByPqtCentralDesc($mdf);
            if($dataCentral == null){
                throw new Exception('MDF no registrado.');
            }
            $idCentral      = $dataCentral['idCentral'];
                        
            $existSisego = $this->m_utils->existeSisego($sisego);
            if($existSisego['count'] >= 1){
                $data['itemplan']   =   $existSisego['itemplan'];
                throw new Exception('SISEGO ya se encuentra registrado.');
            }
            
            $codigo_cluster = $this->m_utils->getCodCluster();
            $dataPadre = array(
                               'sisego'         => $sisego,
                               'fecha_envio'    => $fecha_envio,
                               'id_trama'       => $id_trama,
                               'segmento'       => $segmento,
                               'idCentral'      => $idCentral,
                               'idSubProyecto'  => $idSubproy,
                               'fecha_registro' => $this->fechaActual(),
                               'estado'         => 0,
                               'codigo_cluster' => $codigo_cluster,
                               'coordX'         => $coordX,
                               'coordY'         => $coordY,
                               'cliente'        => $cliente,
                               'flg_tipo'       => 1
            );
            
            $hijosArray = array();
            $hijosArray = json_decode($hijos);
              //log_message('error', print_r($hijosArray, true));
            if($hijosArray!=null){
                foreach($hijosArray as $row){
                    $row->codigo_cluster = $codigo_cluster;
                } 
            }
            $data   =   $this->m_planobra->insertClusterFromSisego($dataPadre, $hijosArray);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('Error interno al registrar el planobra_cluster - hijos.');                                                            
            }else{
                $data['codigo'] = $codigo_cluster;
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function aprobarCancelarCluster(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            header('Access-Control-Allow-Origin: *');
            /***DATOS FROM SISEGOS***/
            $codigo      = $this->input->post('codigo');
            $sisego      = $this->input->post('sisego');
            $estado      = $this->input->post('estado');//1 aprobado  y 2 cancelado

            if($estado  ==  1){
                $datosCluster = $this->m_planobra->getDatosClusterByCod($codigo);
                if($datosCluster!=null){
                    $this->m_planobra->deleteLogImportPlanObraSub();
                    $idProyecto     = $datosCluster['idProyecto'];
                    $idzonal        = $datosCluster['idZonal'];
                    $idSubProyecto  = $datosCluster['idSubProyecto'];
                    $idCentral      = $datosCluster['idCentral'];
                    $idEmpresaColab = $datosCluster['idEmpresaColab'];
                    $indicador      = $datosCluster['sisego'];
                    $eelec          = 6;
                    $estadoplan     = ESTADO_PLAN_PRE_DISENO;
                    $fase           = ID_FASE_ANIO_CREATE_ITEMPLAN;//2019
                    $cantidadTroba  = 0;
                    $fechaInicio    = $this->fechaActual();
                    $fechaCreacion  = $this->fechaActual();
                    $nombreplan     = $indicador." - ";//falta el cliente
                    $uip            = 0;
                    $cordx          = $datosCluster['coordX'];
                    $cordy          = $datosCluster['coordY'];
                    $has_coti       = '0';//YA FUE COTIZADO
                    $fecEjec        = strtotime ( '+'.$datosCluster['tiempo_ejecu_planta_externa'].' day' , strtotime ( $fechaInicio) ) ;
                    $fecPrevistaEjec= date ( 'Y-m-j' , $fecEjec );
                    
                    $itemplan   = $this->m_planobra->generarCodigoItemPlan($idProyecto, $idzonal);
                    $data = $this->m_planobra->insertarPlanobraCluster( $itemplan,      $idProyecto,    $idSubProyecto, $idCentral, 
                                                                        $idzonal,       $idEmpresaColab,  $eelec,     $estadoplan, $fase,
                                                                        $fechaInicio,   $nombreplan,    $indicador, $uip, 
                                                                        $cordx, $cordy, $cantidadTroba, $has_coti,  $fechaCreacion, $fecPrevistaEjec);
                    
                    if($data['error'] == EXIT_ERROR){
                        throw new Exception('1) Error interno al registrar el itemplan. CLUSTER');
                    }else{
                        $itemplanData= $this->m_planobra->obtenerUltimoRegistro();
                        if($itemplanData!=null){
                            $this->m_planobra->insertarLogPlanObra($itemplanData,0,ID_TIPO_PLANTA_EXTERNA);//ID USUARIO = 0 FROM SISEGO
                            //UPDATE CLUSTER
                            $datoArray = array("estado" => 2,//cluster aprobado
                                                "itemplan" => $itemplanData,
                                                "fecha_aprobacion" => $this->fechaActual()
                            );
                            $this->m_planobra->updateEstadoCluster($codigo, $datoArray);                            
                            /////////////////////////////AUTO ADJUDICAR ITEMPLAN
                            $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplanData);
                            $this->session->set_userdata('has_fo', $conEsta['fo']);
                            $this->session->set_userdata('has_coax', $conEsta['coaxial']);
                            $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubProyecto);
                            $curHour = date('H');
                            if($curHour >= 13){//13:00 PM
                                $dias = ($dias + 1);
                            }
                            $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
                            $idFechaPreAtencionFo= date ( 'Y-m-j' , $nuevafecha );
                            $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplanData, $idSubProyecto, $idCentral, $idEmpresaColab, null, $idFechaPreAtencionFo);                            
                            $data['itemplan']   = $itemplanData;
                            $data['msj']        = 'Registro Exitoso.';
                            $dataArray = array(
                                                    'codigo_cluster' => $codigo,
                                                    'fecha'          => $this->fechaActual(),
                                                    'id_usuario'     => 1645,//SISEGO
                                                    'estado'         => 2
                                                );
                            $this->m_utils->insertLogCotizacionInd($dataArray);
                            $this->m_utils->saveLogSigoplus('TRAMA APROBAR COTIZACION - CREAR ITEMPLAN', $codigo, $itemplanData, '', $indicador, $idEmpresaColab, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3);
                        }else if($itemplanData==null){
                            $data['itemplan']   = null;
                            $data['msj'] = 'Error al obtener el itemplan.';
                        }                        
                    }
                   
                }else{
                    throw new Exception('CODIGO CLUSTER INVALIDO.:'.$codigo);
                }
            }else if($estado    ==  2){//cluster cancelado
                //UPDATE CLUSTER
                $datoArray = array("estado" => 3,//cluster cancelado
                                   "fecha_aprobacion" => $this->fechaActual()
                );
                $data = $this->m_planobra->updateEstadoCluster($codigo, $datoArray);
                $data['msj']    = 'Se cancelo el cluster';
                $dataArray = array(
                                        'codigo_cluster' => $codigo,
                                        'fecha'          => $this->fechaActual(),
                                        'id_usuario'     => 1645,//SISEGO
                                        'estado'         => 3
                                    );
                $this->m_utils->insertLogCotizacionInd($dataArray);
                $this->m_utils->saveLogSigoplus('TRAMA RECHAZAR COTIZACION', $codigo, 'CANCELADO', null, null, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3); 
            }else if($estado    ==  3){//si se pide rechazar varias cotizaciones de un sisego
                $datoArray = array  (
                                        "estado" => 3,
                                        "fecha_aprobacion" => $this->fechaActual()
                                    );
                $this->m_planobra->updateEstadoBySisegoCotizacion($sisego, $datoArray, 2);
                $data['msj']    = 'Se cancelo el cluster';
                if($data['error'] == EXIT_SUCCESS) {
                    $data = $this->m_utils->insertLogCotizacionIndBySisego($sisego);
                    $this->m_utils->saveLogSigoplus('TRAMA RECHAZAR COTIZACION', $codigo, 'CANCELADO', null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3);                
                }
            }else{
                throw new Exception('ESTADO RECIBIDO INVALIDO:'.$estado);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA APROBAR COTIZACION FROM SISEGO', $codigo,null,null,null,null,null, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 3);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tramaCrearSAM(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            header('Access-Control-Allow-Origin: *');
            /***DATOS FROM SISEGOS***/
            $departamento       = $this->input->post('departamento');
            $pronvincia         = $this->input->post('pronvincia'); 
            log_message('error', '$codigo:'.$departamento);
            log_message('error', '$estado:'.$pronvincia);
           $data['itemplan']    = '18-00000001';
           $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getFactorDeMedicion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $idSubProyecto = $this->input->post('idSupProyecto');
            
            log_message('error', '$idSubProyecto:'.$idSubProyecto);
            
            $infoFactorMedicion = $this->m_utils->getFactorDeMedicionXIdSubProyecto($idSubProyecto);
            $data['descFactorMedicion']    = $infoFactorMedicion['descPqtTipoFactorMedicion'];
            $data['idFactorMedicion']     = $infoFactorMedicion['idPqtTipoFactorMedicion'];
            
            log_message('error', '$descFactorMedicion:'.$data['descFactorMedicion']);
            log_message('error', '$idFactorMedicion:'.$data['idFactorMedicion']);
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
}