<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_edit_crecimiento_vertical extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_edit_crecimiento_vertical');
        $this->load->model('mf_crecimiento_vertical/m_crecimiento_vertical');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_aprob_cv');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_reg_cv_negocio');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
               
               $item = (isset($_GET['item']) ? $_GET['item'] : '');
               $data['cmbSubProyecto']    = __buildComboSubProyectoCV(array(97, 98), $item);
               $data['item'] = $item;
               $dataItemCV =  $this->m_edit_crecimiento_vertical->getInfoItemCV($item);
               
               $data['nombre_urb_cchh']     =   $dataItemCV['nombre_urb_cchh'];
               $data['nombreProyecto']      =   $dataItemCV['nombreProyecto'];
               $data['idSubProyecto']       =   $dataItemCV['idSubProyecto'];
               $data['tipo_urb_cchh']       =   $dataItemCV['tipo_urb_cchh'];
               $data['tipo_via']            =   $dataItemCV['tipo_via'];
               $data['manzana']             =   $dataItemCV['manzana'];
               $data['lote']                =   $dataItemCV['lote'];
               $data['blocks']              =   $dataItemCV['blocks'];
               $data['pisos']               =   $dataItemCV['pisos'];
               $data['depa']                =   $dataItemCV['depa'];
               $data['depa_habitados']      =   $dataItemCV['depa_habitados'];
               $data['avance']              =   $dataItemCV['avance'];
               $data['fec_termino_constru'] =   $dataItemCV['fec_termino_constru'];
               $data['ruc_constructora']    =   (($dataItemCV['ruc_constructora'] != '') ? $dataItemCV['ruc_constructora']: '00000000000' );
               $data['observaciones']       =   $dataItemCV['observaciones'];
               $data['nombre_constructora'] =   $dataItemCV['nombre_constructora'];
               $data['contacto_1']          =   $dataItemCV['contacto_1'];
               $data['telefono_1_1']        =   $dataItemCV['telefono_1_1'];
               $data['telefono_2_1']        =   $dataItemCV['telefono_2_1'];
               $data['email_1']             =   $dataItemCV['email_1'];
               $data['contacto_2']          =   $dataItemCV['contacto_2'];
               $data['telefono_1_2']        =   $dataItemCV['telefono_1_2'];
               $data['telefeono_2_2']       =   $dataItemCV['telefeono_2_2'];
               $data['email_2']             =   $dataItemCV['email_2'];               
               $data['coordenada_x']        =   $dataItemCV['coordenada_x'];
               $data['coordenada_y']        =   $dataItemCV['coordenada_y'];
               $data['estado_edificio']     =   $dataItemCV['estado_edificio'];
               $data['direccion']           =   "'".utf8_decode($dataItemCV['direccion'])."'";
               $data['numero']              =   "'".utf8_decode($dataItemCV['numero'])."'";
               $data['idCentral']           =   $dataItemCV['idCentral'];
               $data['competencia']         =   $dataItemCV['competencia'];
               $data['prioridad']           =   $dataItemCV['prioridad'];
               $data['opcionesAvance']      =   $this->getHTMLComboPorcentajes($dataItemCV['avance']);
               $data['porcentaje_actual']   =   $dataItemCV['avance'];
               $data['operador']            =   $dataItemCV['operador'];
               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $data['listaSubProCV'] =  $this->m_utils->getAllSubProyectosCV();
               $data['listaNodos']    =  $this->m_utils->getOnlyMDF();
               
               $info = $this->getMarkersCV();
               $data['marcadores']    =  $info['markers'];
               $data['info_markers']  =  $info['info_markers'];
                
               $info2017 = $this->getMarkers2017CV();
               $data['marcadores_2017']    =  $info2017['markers'];
               $data['info_markers_2017']  =  $info2017['info_markers'];
                
               $infoODF =   $this->getODFCV();
               $data['marcadores_odf']    =  $infoODF['markers'];
               $data['info_markers_odf']  =  $infoODF['info_markers'];
               
               $data['tablaLogMoviemientos'] = $this->getHTMLTablaMovimientos($this->m_edit_crecimiento_vertical->getAllMovimientosCVPorMes($item));
               
               $permisos    =  $this->session->userdata('permisosArbol');
               $result      =  $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_BANDEJA_EDIT);
               $data['opciones'] = $result['html'];
               if($result['hasPermiso'] == true){
                     $this->load->view('vf_crecimiento_vertical/v_edit_crecimiento_vertical',$data);
               }else{
                   redirect('login','refresh');
               }
         }else{
             redirect('login','refresh');
        }
             
    }
    
    public function saveItemCV(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $itemplan       = $this->input->post('itemplan');
            //$idSubProyecto  = $this->input->post('selectSubPro');
            $subProyectoDesc = $this->input->post('subProyectoDesc');            
            $idTipoUrba     = $this->input->post('selectTipoUrb');            
            $nombreUrba     = $this->input->post('txt_NombreUrb');
            $idTipoVia      = $this->input->post('selectTipoVia');
            $direccion      = $this->input->post('txt_direccion');
            $numero         = $this->input->post('txt_numero');
            $manzana        = $this->input->post('txt_manzana');
            $lote           = $this->input->post('txt_lote');
            $nombreProyecto = $this->input->post('txt_nombre_proyecto');
            $blocke         = $this->input->post('txt_blocks');
            $num_pisos      = $this->input->post('txt_pisos');
            $num_depa       = $this->input->post('txt_departamentos');
            $num_depa_habi  = $this->input->post('txt_dep_habitados');
            $avance         = $this->input->post('txt_avance');
            $fec_termino    = $this->input->post('txt_fec_termino');     
            $observacion    = $this->input->post('inputObservacion');
            $ruc            = $this->input->post('txt_ruc');
            $nombre_constru = $this->input->post('txt_nombre_constru');
            $contacto_1     = $this->input->post('txt_contacto1');
            $telefono_1_1   = $this->input->post('txt_telefono11');
            $telefono_1_2   = $this->input->post('txt_telefeono12');
            $email_1        = $this->input->post('email1');
            $contacto_2     = $this->input->post('txt_contacto2');
            $telefono_2_1   = $this->input->post('txt_telefono21');
            $telefono_2_2   = $this->input->post('txt_telefono22');
            $email_2        = $this->input->post('txt_email2');
            $coord_x        = $this->input->post('coor_x');
            $coord_y        = $this->input->post('coor_y');
            $departamento   = $this->input->post('departamento');
            $provincia      = $this->input->post('provincia');
            $distrito       = $this->input->post('distrito');
            $accion         = $this->input->post('accion');
            $estado_edifi   = $this->input->post('selectEstadoEdi');
           
            $prioridad      = $this->input->post('selectPrioridad');
            $competencia    = $this->input->post('selectCompetencia');
            $per_actu       = $this->input->post('per_actu');
            

            $tipoSave       = $this->input->post('tipo_save');
            $tipoSubProy    = $this->input->post('selectTipoProy');///HFC O FTTH
            $operador       = $this->lib_utils->removeEnterYTabs($this->input->post('txtOperador'));
            $eelec = 6;
            /////
            $idCentral     = $this->input->post('idCentral');
            $idSubProyecto = $this->input->post('idSubProyecto');
            $idEECC        = $this->input->post('idEmpresaColab');
            $idZonal       = $this->input->post('idZonal');
            $fase          = $this->input->post('idFase');
            $idTipoSubProyectoBOI = $this->input->post('idTipoSubProyecto');
            $id_plan       = $this->input->post('plan_id_mes');
            ////
            $fechaInicio = date("Y-m-d");
            $indicador = '';
            $uip = 0;
            $cantidadTroba = 0;
            $this->db->trans_begin();

            if($idEECC == null){
                throw new Exception('ERROR INTERNO, CONTRATA NULO 1');
            }
            
            if($idSubProyecto == null){
                throw new Exception('ERROR INTERNO, SUBPROYECTO NULO');
            }

            if($idCentral == null){
                throw new Exception('ERROR INTERNO, CENTRAL NULO');
            }

            if($idZonal == null) {
                throw new Exception('ERROR INTERNO, ZONAL NULO');
            }
            
			if($num_depa == null) {
				throw new Exception('DEBE INGRESAR EL NRO DE DEPARTAMENTOS.');
			}
            // $arrayCountPlan = $this->m_planobra->getCountPlan($idSubProyecto, $plan_id_mes, $fase);
            
            // if($arrayCountPlan['flg_top_cantidad'] == 1) {//SI LLEGO AL TOPE DE LA CANTIDAD DEL PLAN
            //     throw new Exception('El plan ya llego al limite, seleccionar otra planificación.');
            // }

            $paquetizado_fg = null;
            $idCentralPqt    = null;
            if($idTipoSubProyectoBOI == 1) { // SI ES BUCLE
                $paquetizado_fg = 2;
                $idCentralPqt    = $idCentral;
            }
          
            // _log('=>>>>>>>'.$itemplan.'|'.$avance.'|'.$per_actu.'|'.print_r($_FILES, true));
            $uploadfile =   null;
            // if($avance >= PORCENTAJE_MINIMO_CV_TO_OBRA){
                $uploaddir =  'uploads/avance_cv/'.$itemplan.'/';//ruta final del file
                $uploadfile = $uploaddir . basename($_FILES['file']['name']);
                if (! is_dir ( $uploaddir))
                    mkdir ( $uploaddir, 0777 );
                
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);  
            // }

            $data = $this->m_edit_crecimiento_vertical->updateItemplanCV(ID_PROYECTO_CRECIMIENTO_VERTICAL, $idSubProyecto, $eelec, $fechaInicio, $nombreProyecto, $indicador, $uip, $coord_x, 
                                                                  $coord_y,$cantidadTroba, $departamento, $provincia, $distrito, $idTipoUrba, $nombreUrba, $idTipoVia, 
                                                                  $direccion, $numero, $manzana, $lote, $blocke, $num_pisos, $num_depa, $num_depa_habi, $avance, 
                                                                  $fec_termino, $observacion, $ruc, $nombre_constru, $contacto_1, 
                                                                  $telefono_1_1, $telefono_1_2, $email_1, $contacto_2, $telefono_2_1, $telefono_2_2, $email_2, 
                                                                  $accion, $itemplan, $estado_edifi, $idCentral, $competencia, $prioridad, $idEECC, $uploadfile, $operador, 
                                                                  $paquetizado_fg, $idCentralPqt, $idZonal);
                      
            if($data['error']   ==  EXIT_SUCCESS){
                if($avance >= PORCENTAJE_MINIMO_CV_TO_OBRA){
                    $hasAprobacion = $this->m_utils->getEstadoAprobCVByItemplan($itemplan);
                    if($hasAprobacion!=null && $hasAprobacion == '0'){
                        $flgAbrePorOk = $this->m_utils->getFlgPorcAbrePue($id_plan);

                        if($flgAbrePorOk == 1) {
                            $respSolOc = $this->m_utils->generarSolicitudOC($id_plan);

                            // if($respSolOc == 3) {
                            //     $data['error'] = EXIT_ERROR;
                            //     throw new Exception('NO TIENE PRESUPUESTO PARA CREAR UNA SOLICITUD OC.');
                            // }
            
                            // if($respSolOc == 6) {
                            //     $data['error'] = EXIT_ERROR;
                            //     throw new Exception('NO TIENE PEP CONFIGURADA.');
                            // }
            
                            // if($respSolOc == 2 || $respSolOc == 5) {
                            //     $data['error'] = EXIT_ERROR;
                            //     throw new Exception("No se pudo cotizar por error del presupuesto.");
                            // }
                        }
                        //$cont   =   $this->m_edit_crecimiento_vertical->existTablaBucle($itemplan);                    
                                          
                            /////////////////////////////AUTO ADJUDICAR ITEMPLAN
                            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);                        
                            $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubProyecto);
                            
                            if($dias    !=  null){//si tiene dias adjudicamos
                                $curHour = date('H');
                                if($curHour >= 13){//13:00 PM
                                    $dias = ($dias + 1);
                                }
                                $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
                                $idFechaPreAtencionFo = date ( 'Y-m-j' , $nuevafecha );//VALIDAR SI ES NECESARIO ADJUDICAR SI YA NO ESTA EN PRE REGISTRO
                                
                                $conEsta = $this->m_edit_crecimiento_vertical->countFOAndCoaxByItemplan($itemplan);
                                $this->session->set_userdata('has_fo', $conEsta['fo']);
                                $this->session->set_userdata('has_coax', $conEsta['coaxial']);
                                
                                $data = $this->m_edit_crecimiento_vertical->adjudicarItemplanFromCV($itemplan, $idSubProyecto, $idFechaPreAtencionFo, $idFechaPreAtencionFo, $idEstadoPlan, $idTipoSubProyectoBOI);
                              
                            }else{//si no tiene dias dejamos en pre_diseÃƒÆ’Ã‚Â±o
                                $data = $this->m_bandeja_aprob_cv->aprobarItemplan(ESTADO_CV_APROBADO, $itemplan, $idEstadoPlan);
                            }
                            ////////////////////////////////////////////////////////////////
                            if($data['error']==EXIT_ERROR){
                                throw new Exception('Error al adjudicar.');
                            }
                            $this->db->trans_commit();
                           /* $arraLicencias = array();
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
                            if($data['error']==EXIT_ERROR){
                                $this->db->trans_rollback();
                                throw new Exception('Error al generar Licencias.');                               
                            } else {
                                $this->db->trans_commit();
                            } */
                    }else{
                        $this->db->trans_commit(); 
                    }
                } else {
                    $this->db->trans_commit(); 
                }        
            }else{
                throw new Exception($data['msj']);
            }
           
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function existeConstructora(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ruc  = $this->input->post('ruc');
            $info = $this->m_edit_crecimiento_vertical->getInfoConstructora($ruc);
            if($info!=null){
                $data['hasInfo']      = '1';
                $data['ruc']          = $info['ruc'];
                $data['nombre']       = $info['nombre'];
                $data['contacto_1']   = $info['contacto_1'];
                $data['telefono_1_1'] = $info['telefono_1_1'];
                $data['telefono_2_1'] = $info['telefono_2_1'];
                $data['email_1']      = $info['email_1'];
                $data['contacto_2']   = $info['contacto_2'];
                $data['telefono_1_2'] = $info['telefono_1_2'];
                $data['telefono_2_2'] = $info['telefono_2_2'];
                $data['email_2']      = $info['email_2'];
            }else{
                $data['hasInfo'] = '0';
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
    
    public function getMarkersCV(){
        //$data = array();
        $markers = array();
        $infoMarkers = array(); 
        $informacion = $this->m_utils->getALLProyectosCV();
        foreach($informacion->result() as $row){    
            $temp = array($row->nombre_proyecto, $row->coordenada_y, $row->coordenada_x, $row->idEstadoPlan);
            array_push($markers, $temp);
            
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Itemplan:</strong></td>
                                        <td>'.$row->itemplan.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado Plan:</strong></td>
                                        <td>'.$row->estadoPlanDesc.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sub Proyecto:</strong></td>
                                        <td>'.$row->subProyectoDesc.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nombre Proyecto:</strong></td>
                                        <td>'.$row->nombre_proyecto.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Direccion:</strong></td>
                                        <td>'.$row->direccion.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Numero:</strong></td>
                                        <td>'.$row->numero.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada X:</strong></td>
                                        <td>'.$row->coordenada_x.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada Y:</strong></td>
                                        <td> '.$row->coordenada_y.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Contrata:</strong></td>
                                        <td> '.$row->empresaColabDesc.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Avance:</strong></td>
                                        <td> '.$row->avance.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha Termino:</strong></td>
                                        <td>'.$row->fec_termino_constru.'</td>
                                    </tr>>
                                </tbody>
                            </table>');
          
            array_push($infoMarkers, $temp2);
        }
        $data['markers'] = $markers;
        $data['info_markers'] = $infoMarkers;
        return $data;
    }
    
    public function getHTMLComboPorcentajes($porcentaje){
        $html   =   '';
        $cont   =   10;
     
        for($i=0; $i<10; $i++){ 
            if($porcentaje == $cont){                
                $html .= '<option selected value="'.$cont.'">'.$cont.'</option>';
            }else if($porcentaje < $cont){
                $html .= '<option value="'.$cont.'">'.$cont.'</option>';
            }
            $cont = $cont + 10;
        }
        return $html;
    }
    
    public function getMarkers2017CV(){
        //$data = array();
        $markers = array();
        $infoMarkers = array();
        $informacion = $this->m_utils->getALLOLDProyectosCV();
        foreach($informacion->result() as $row){
            $temp = array($row->segmento, $row->coord_y, $row->coord_x);
            array_push($markers, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row->codigo.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado Plan:</strong></td>
                                        <td>Terminado</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Segmento:</strong></td>
                                        <td>'.$row->segmento.'</td>
                                    </tr>
                 
                                    <tr>
                                        <td><strong>Direccion:</strong></td>
                                        <td>'.$row->direccion.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Numero:</strong></td>
                                        <td>'.$row->numero.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada X:</strong></td>
                                        <td>'.$row->coord_x.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada Y:</strong></td>
                                        <td> '.$row->coord_y.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Contrata:</strong></td>
                                        <td> '.$row->contrata.'</td>
                                    </tr>
                  
                                    <tr>
                                        <td><strong>Fecha Termino:</strong></td>
                                        <td>'.$row->fecha_termino_cableado.'</td>
                                    </tr>>
                                </tbody>
                            </table>');
            array_push($infoMarkers, $temp2);
        }
        $data['markers'] = $markers;
        $data['info_markers'] = $infoMarkers;
        return $data;
    }
    
    public function getODFCV(){
        //$data = array();
        $markers = array();
        $infoMarkers = array();
        $informacion = $this->m_utils->getALLODFCV();
        foreach($informacion->result() as $row){
            $temp = array($row->codigo, $row->coord_y, $row->coord_x);
            array_push($markers, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row->codigo.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Capacidad:</strong></td>
                                        <td>'.$row->capacidad.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fibras Asignadas:</strong></td>
                                        <td>'.$row->cantidad_fibras_asignadas.'</td>
                                    </tr>
         
                                    <tr>
                                        <td><strong>Fibras Libres:</strong></td>
                                        <td>'.$row->cantidad_fibras_libres.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fibras Ocupadas:</strong></td>
                                        <td>'.$row->cantidad_fibras_ocupadas.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fibras Reservadas:</strong></td>
                                        <td>'.$row->cantidad_fibras_reservadas.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Situacion:</strong></td>
                                        <td> '.$row->situacion.'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada X:</strong></td>
                                        <td> '.$row->coord_x.'</td>
                                    </tr>
    
                                    <tr>
                                        <td><strong>Coordenada Y:</strong></td>
                                        <td>'.$row->coord_y.'</td>
                                    </tr>>
                                </tbody>
                            </table>');
            array_push($infoMarkers, $temp2);
        }
        $data['markers'] = $markers;
        $data['info_markers'] = $infoMarkers;
        return $data;
    }
        
    public function getHTMLTablaMovimientos($listaPTR){
    
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center;">MES</th>
                            <th style="text-align: center;"># MOVIMIENTOS</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($listaPTR->result() as $row){
    
            $html .=' <tr '.(($row->same == 1) ? 'style="background-color:#ffe8aa"': '' ).'>                            
						<td>'.$row->desc_mes.'</td>
						<td><a style="color:blue" data-item="'.$row->itemplan.'" data-mes="'.$row->mes.'" onclick="getDetalleLog(this)" >'.$row->cont.'</a></td>
			         </tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function getDatalleLogMovimientos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
    
            $itemplan   = $this->input->post('itemplan');
            $mes        = $this->input->post('mes');           
            $data['tablaDetalleLog'] = $this->getHTMLTablaMovimientosLogMes($this->m_edit_crecimiento_vertical->getAllMovimientosCV($itemplan, $mes));
            $data['itemplan'] = $itemplan;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getHTMLTablaMovimientosLogMes($listaPTR){
    
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>USUARIO</th>
                            <th>AVANCE</th>
                            <th>FECHA REGISTRO</th>
                            <th>EVIDENCIA</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($listaPTR->result() as $row){
    
            $html .=' <tr>
						<td>'.$row->usuario.'</td>
						<td>'.$row->avance.'</td>
						<td>'.$row->fecha_registro.'</td>
						<td>'.(($row->path_file!=null) ? '<a href="'.$row->path_file.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>' : '').'</td>
					</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}