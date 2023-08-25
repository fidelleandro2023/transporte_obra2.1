<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_crecimiento_vertical extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_crecimiento_vertical');
        $this->load->model('mf_crecimiento_vertical/m_edit_crecimiento_vertical');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_aprob_cv');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
               
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
               
               $permisos              =  $this->session->userdata('permisosArbol');
               $result                =  $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_PRE_REGISTRO_CV);
               $data['opciones']      =  $result['html'];
               if($result['hasPermiso'] == true){
                     $this->load->view('vf_crecimiento_vertical/v_crecimiento_vertical',$data);
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
            
            $idTipoUrba     = $this->input->post('selectTipoUrb');            
            $nombreUrba     = $this->lib_utils->removeEnterYTabs($this->input->post('txt_NombreUrb'));
            $idTipoVia      = $this->input->post('selectTipoVia');
            $direccion      = $this->lib_utils->removeEnterYTabs($this->input->post('txt_direccion'));
            $numero         = $this->lib_utils->removeEnterYTabs($this->input->post('txt_numero'));
            $manzana        = $this->lib_utils->removeEnterYTabs($this->input->post('txt_manzana'));
            $lote           = $this->lib_utils->removeEnterYTabs($this->input->post('txt_lote'));
            $nombreProyecto = $this->lib_utils->removeEnterYTabs($this->input->post('txt_nombre_proyecto'));
            $blocke         = $this->lib_utils->removeEnterYTabs($this->input->post('txt_blocks'));
            $num_pisos      = $this->lib_utils->removeEnterYTabs($this->input->post('txt_pisos'));
            $num_depa       = $this->lib_utils->removeEnterYTabs($this->input->post('txt_departamentos'));
            $num_depa_habi  = $this->lib_utils->removeEnterYTabs($this->input->post('txt_dep_habitados'));
            $avance         = $this->input->post('txt_avance');
            $fec_termino    = $this->input->post('txt_fec_termino');     
            $observacion    = $this->lib_utils->removeEnterYTabs($this->input->post('inputObservacion'));
            $ruc            = $this->lib_utils->removeEnterYTabs($this->input->post('txt_ruc'));
            $nombre_constru = $this->lib_utils->removeEnterYTabs($this->input->post('txt_nombre_constru'));
            $contacto_1     = $this->lib_utils->removeEnterYTabs($this->input->post('txt_contacto1'));
            $telefono_1_1   = $this->lib_utils->removeEnterYTabs($this->input->post('txt_telefono11'));
            $telefono_1_2   = $this->lib_utils->removeEnterYTabs($this->input->post('txt_telefeono12'));
            $email_1        = $this->lib_utils->removeEnterYTabs($this->input->post('email1'));
            $contacto_2     = $this->lib_utils->removeEnterYTabs($this->input->post('txt_contacto2'));
            $telefono_2_1   = $this->lib_utils->removeEnterYTabs($this->input->post('txt_telefono21'));
            $telefono_2_2   = $this->lib_utils->removeEnterYTabs($this->input->post('txt_telefono22'));
            $email_2        = $this->lib_utils->removeEnterYTabs($this->input->post('txt_email2'));
            $coord_x        = $this->input->post('coor_x');
            $coord_y        = $this->input->post('coor_y');
            $departamento   = $this->input->post('departamento');
            $provincia      = $this->input->post('provincia');
            $distrito       = $this->input->post('distrito');
            $accion         = $this->input->post('accion');
            $estado_edifi   = $this->lib_utils->removeEnterYTabs($this->input->post('selectEstadoEdi'));
            $mdf            = $this->input->post('seletContrata');
            $prioridad      = $this->lib_utils->removeEnterYTabs($this->input->post('selectPrioridad'));
            $competencia    = $this->lib_utils->removeEnterYTabs($this->input->post('selectCompetencia'));
            $fase           = $this->input->post('selectFase') ? $this->input->post('selectFase') : 2;
            $operador       = $this->lib_utils->removeEnterYTabs($this->input->post('txtOperador'));
            
            $idCentral = 1;//POR DEFECTO
            $idZonal = 0;//POR DEFECTO
            //$idEECC = (($this->session->userdata('eeccSession')!=null) ? $this->session->userdata('eeccSession') : 0);
            $infoCentra = $this->m_utils->getInfocentralByIdCentral($mdf);//CENTRAL GENERICA PARA QUANTA
            $idCentral  =   $infoCentra['idCentral'];
            $idZonal    =   $infoCentra['idZonal'];
            $idEECC = null;           
            
            $infoSubPro = $this->m_crecimiento_vertical->getSubProByNodo($mdf);
            $idTipoSubProyectoBOI = $this->m_crecimiento_vertical->getBucleIntegralBySubProy($idSubProyecto);
            _log('$idTipoSubProyectoBOI>>>>>'.$idTipoSubProyectoBOI);
            
            if($infoSubPro['cont']  ==  1){
               if($infoSubPro['flg_subproByNodoCV']!=null){
                   $idSubProyecto = $infoSubPro['flg_subproByNodoCV'];
                   $idEECC     =   $infoCentra['idEmpresaColabCV'];
                   $cont = 0;
               }else{
                   $idSubProyecto = ID_SUB_PROYECTO_CV_BUCLE;
                   $idEECC     =   $infoCentra['idEmpresaColab'];
                   $cont = 1;
               }
            }else{
                throw new Exception('ERROR INTERNO, NODO NO ENCONTRADO.');
            }
           
            if($idEECC == null){
                throw new Exception('ERROR INTERNO, NODO SIN CONTRATA ASIGNADA');
            }
            
            $eelec = 6;
            //$fase = 1;
            $fechaInicio = date("Y-m-d");
            $indicador = '';
            $uip = 0;
            $cantidadTroba = 0;
            
            $base_itemplan = $this->m_planobra->generarCodigoItemPlan(ID_PROYECTO_CRECIMIENTO_VERTICAL, $idZonal);
            
            $data = $this->m_crecimiento_vertical->saveItemplanCV($base_itemplan,ID_PROYECTO_CRECIMIENTO_VERTICAL, $idSubProyecto, $idCentral, $idZonal, $idEECC, 
                                                                  $eelec, ESTADO_PLAN_PRE_REGISTRO, $fase, $fechaInicio, $nombreProyecto, $indicador, $uip, $coord_x, 
                                                                  $coord_y,$cantidadTroba, $departamento, $provincia, $distrito, $idTipoUrba, $nombreUrba, $idTipoVia, 
                                                                  $direccion, $numero, $manzana, $lote, $blocke, $num_pisos, $num_depa, $num_depa_habi, $avance, 
                                                                  $fec_termino, $observacion, $ruc, $nombre_constru, $contacto_1, 
                                                                  $telefono_1_1, $telefono_1_2, $email_1, $contacto_2, $telefono_2_1, $telefono_2_2, $email_2, $accion, 
                                                                  $estado_edifi, $competencia, $prioridad, $operador);
            
            $last_itemplan = $this->m_utils->getLastItemplanByPrefijoItemplan($base_itemplan);
            
            if($avance >= PORCENTAJE_MINIMO_CV_TO_OBRA){
               
                $hasAprobacion = $this->m_utils->getEstadoAprobCVByItemplan($last_itemplan);
                if($hasAprobacion!=null && $hasAprobacion == '0'){
                    //$cont   =   $this->m_edit_crecimiento_vertical->existTablaBucle($itemplan);
                    if($cont   >=  1){//SI EXISTE EN LA TABLA DE CV BUCLE
                        /////////////////////////////AUTO ADJUDICAR ITEMPLAN
                        $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($last_itemplan);
                        $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubProyecto);
            
                        if($dias    !=  null){//si tiene dias adjudicamos
                            $curHour = date('H');
                            if($curHour >= 13){//13:00 PM
                                $dias = ($dias + 1);
                            }
                            $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
                            $idFechaPreAtencionFo = date ( 'Y-m-j' , $nuevafecha );//VALIDAR SI ES NECESARIO ADJUDICAR SI YA NO ESTA EN PRE REGISTRO
            
                            $conEsta = $this->m_edit_crecimiento_vertical->countFOAndCoaxByItemplan($last_itemplan);
                            $this->session->set_userdata('has_fo', $conEsta['fo']);
                            $this->session->set_userdata('has_coax', $conEsta['coaxial']);
            
                            $info = $this->m_edit_crecimiento_vertical->adjudicarItemplanFromCV($last_itemplan, $idSubProyecto, $idFechaPreAtencionFo, $idFechaPreAtencionFo, $idEstadoPlan, $idTipoSubProyectoBOI);
            
                        }else{//si no tiene dias dejamos en pre_diseno
                            $this->m_bandeja_aprob_cv->aprobarItemplan(ESTADO_CV_APROBADO, $last_itemplan, $idEstadoPlan);
                        }
                        ////////////////////////////////////////////////////////////////
            
                    }else{// SI NO EXISTE PASA EN OBRA Y SE PONE COMO EC A QUATAM O CAM
                        //FALTA PONER A CAM O QUATAM COMO ECC
                        $output = $this->m_edit_crecimiento_vertical->updateCVToObra($last_itemplan);
                        //registro licencias
                        
                        $arraLicencias = array();
                        $listaEstacionAnclas = $this->m_utils->getEstacionesAnclasByItemplan($last_itemplan);
                        foreach($listaEstacionAnclas as $row){
                            $dataLicencias = array(
                                'idEntidad'         => 2,
                                'idEstacion'        => $row->idEstacion,
                                'itemPlan'          => $last_itemplan,
                                'id_usuario_reg'    => $this->session->userdata('idPersonaSession'),
                                'fecha_registro'    => $this->fechaActual(),
                                'flg_validado'      => 0
                            );
                        
                            array_push($arraLicencias, $dataLicencias);
                        }
                        
                        $data = $this->m_utils->insertLicenciasFromCV($arraLicencias);
                        if($data['error']==EXIT_ERROR){
                            throw new Exception('Error al generar Licencias.');
                        }
                    }
                }else{
            
                }
            }
            
            $data['lastItem']    = $last_itemplan;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
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
            $info = $this->m_crecimiento_vertical->getInfoConstructora($ruc);
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
            /*$temp2 = array('<p>
                                    <strong>Itemplan:</strong>'.$row->itemplan.'<br />
                                    <strong>Sub Proyecto:</strong>'.$row->subProyectoDesc.'<br />
                                    <strong>Nombre Proyecto:</strong>'.$row->nombre_proyecto.'<br />
                                    <strong>Direccion:</strong>'.$row->direccion.'<br />
                                    <strong>Numero:</strong>'.$row->numero.'<br />
                                    <strong>Coordenada X:</strong>'.$row->coordenada_x.'<br />
                                    <strong>Coordenada Y:</strong>'.$row->coordenada_y.'
                                </p>');*/
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
    
     public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}