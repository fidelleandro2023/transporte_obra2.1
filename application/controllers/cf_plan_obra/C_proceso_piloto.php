<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_proceso_piloto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_licencias/M_licencias');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plan_obra/m_proceso_piloto');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $zonas = $this->session->userdata('zonasSession');
            $itemplan = $this->input->get('itemplan');
            $data['dataPiloto'] =  $this->m_utils->getDataProcesoPiloto($itemplan);
            $data['tablaEntidades'] = $this->getInfoEntidades($itemplan);
            $data['itemplan'] = $itemplan; 
            $cto  = $this->m_proceso_piloto->getCtoPlanObra($itemplan);
            $html = $this->makeComboMotivo($this->m_utils->getMotivoAll(4), $cto);
            $data['cmbPlacas'] = __buildComboAuto($itemplan);
            //$data['comboValeReserva'] = __buildComboValeReserva($data['dataPiloto']['placa']);
            $data['tablaBitacoraAsigFacilidad']   = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_REGISTRADO);
            $data['tablaBitacoraReplanteo']       = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_REPLANTEO);
            $data['tablaBitacoraElaboracionFuit'] = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_CON_EXPEDIENTE);
            $data['tablaBitacoraEntregaFuit']     = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_EXPEDIENTE_ENTREGADO);
            $data['tablaBitacoraInsPex']          = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_PEX_EJECUTADO);
            

            $data['tablaKitMaterial']       = $this->getTablaKitMaterial($itemplan, $data['dataPiloto']['placa']);
            $data['comboMotivo'] = $html;
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_plan_obra/v_proceso_piloto', $data);

        } else {

            redirect('login', 'refresh');
        }
    }

    function makeComboMotivo($listaMotivos, $ctoInterno) {
        $html = '<option value="">Seleccionar Motivo</option>';

        foreach ($listaMotivos as $row) {// SI ES DE CTO interno se muestra el motivo AGENDA SI NO NO
            //if($ctoInterno == 'Edificio Monocliente' || $ctoInterno == 'Centro Comercial') {
                $html .= '<option value="'.$row->idMotivo.'">'.$row->motivoDesc.'</option>';
            // } else {
            //     if($row->idMotivo != 47) {
            //         $html .= '<option value="'.$row->idMotivo.'">'.$row->motivoDesc.'</option>';   
            //     }
            // }
        }
        $html = utf8_decode($html);
        return $html;
    }

    // function getDataFluidUnoInit($itemplan) {
    //     $dataPiloto =  $this->m_utils->getDataProcesoPiloto($itemplan);
    //     return $dataPiloto;
    // }

    function getDataPiloto() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan       = $this->input->post('itemplan');

            if($itemplan == null) {
                throw new Exception('error, comunicarse con el programador');
            }
            $dataPiloto =  $this->m_utils->getDataProcesoPiloto($itemplan);
            $data['error'] = EXIT_SUCCESS;
            $data['objPiloto'] = $dataPiloto;
 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data); 
    }

    function registrarFluidUno() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan               = $this->input->post('itemplan');
            $comentarioFluidUno     = $this->input->post('comentarioFluidUno');
            $idMotivoAsigFacilidad  = $this->input->post('idMotivoAsigFacilidad');
            $placa                  = $this->input->post('placa');
            $duracion               = $this->input->post('duracion');

            $this->db->trans_begin();

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            if($comentarioFluidUno == null) {
                throw new Exception('error, comentarioFluidUno null');
            }

            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('error, la sesi&oacute;n a caducado, refrescar la p&aacute;gina.');
            }

            if($duracion == null) {
                throw new Exception('error, duracion null.');                
            }

            if($idMotivoAsigFacilidad == ID_MOTIVO_EJECUCION_EN_PROCESO) {
                if($placa == '' || $placa == null) {
                    throw new Exception('error, placa null');
                }
                
                $arrayData = array('itemplan'                  => $itemplan,
                                   'id_config'                 => ID_CONFIG_ASIGNACION_FACILIDADES,
                                   'comentario_asig_facil'     => $comentarioFluidUno,
                                   'fecha_registro_asig_facil' => $this->fechaActual(),
                                   'duracion_asig_facil'       => $duracion);   

                $data = $this->m_proceso_piloto->registrarProceso($arrayData);
       
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error al registrar el proceso');
                } else {
                    $arrayData = array( 'itemplan' => $itemplan, 
                                        'placa'    => $placa, 
                                        'estado'   => ESTADO_AUTO_OBRA_ACTIVO);
                    $data = $this->m_proceso_piloto->insertAutoObra($arrayData);

                    if($data['error'] == EXIT_ERROR) { 
                        throw new Exception($data['msj']);
                    }
                } 
            
            }
         
            $arrayDataMotivo = array(
                                        'itemplan'          => $itemplan,
                                        'idMotivo'          => $idMotivoAsigFacilidad,
                                        'comentario'        => $comentarioFluidUno,
                                        'idEstado'          => ID_ESTADO_ITEMPLAN_REGISTRADO,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession')
                                    );
            $data = $this->m_proceso_piloto->registrarProcesoPilotoMotivo($arrayDataMotivo);
            
            if($data['error'] == EXIT_ERROR) {
                throw new Exception('error, no se registr&oacute;');
            }

            $this->db->trans_commit();
            $data['tablaBitacora'] = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_REGISTRADO);
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
    }

    function registrarFluidDos() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan           = $this->input->post('itemplan');
            $comentarioFluidDos = $this->input->post('comentarioFluidDos');
            // $fechaCitaFluidDos  = $this->input->post('fechaCitaFluidDos');
            
            $this->db->trans_begin();

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            if($comentarioFluidDos == null) {
                throw new Exception('error, comentarioFluidUno null');
            }
            $arrayData = array('id_config'                 => ID_CONFIG_AGENDA_REPLANTEO,
                               'comentario_agen_replanteo' => $comentarioFluidDos);
            $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('error al registrar el proceso');
            } else {
                $data = $this->m_proceso_piloto->updateEstadoItemplan($itemplan, ID_ESTADO_ITEMPLAN_ASIGNADO, $this->fechaActual());

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error al actualizar el estado');
                }
            }

            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarFluidTres() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan            = $this->input->post('itemplan');
            $comentarioFluidTres = $this->input->post('comentarioFluidTres');
            $idMotivoReplanteo   = $this->input->post('idMotivoReplanteo');
            $duracion            = $this->input->post('duracionReplanteo');
            
            $d = new DateTime($duracion);
            $this->db->trans_begin();

            $duracionReplanteo = $d->format('H:i:s');

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            if($comentarioFluidTres == null) {
                throw new Exception('error, comentarioFluidTres null');
            }
            if($duracionReplanteo == null) {
                throw new Exception('error, duracionReplanteo null');
            }

            if($idMotivoReplanteo == ID_MOTIVO_AGENDA) { //si se ejecuta el proceso, recién cambia el estado del itemplan
                $arrayData = array('id_config'            => ID_CONFIG_AGENDA_REPLANTEO,
                                   'comentario_replanteo' => $comentarioFluidTres);
                $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error al registrar el proceso');
                }
            } else if($idMotivoReplanteo == ID_MOTIVO_EJECUCION_EN_PROCESO) {
                $arrayData = array( 'id_config'            => ID_CONFIG_REPLANTEO,
                                    //'idEstado'                  => ID_ESTADO_ITEMPLAN_REPLANTEO,
                                    'comentario_replanteo' => $comentarioFluidTres,
                                    'duracion_replanteo'   => $duracionReplanteo,
                                    'fecha_reg_replanteo'  => $this->fechaActual());
                $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());     
                
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error al registrar el proceso');
                } else {
                    $data = $this->m_proceso_piloto->updateEstadoItemplan($itemplan, ID_ESTADO_ITEMPLAN_REPLANTEO, $this->fechaActual());  
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('error al actualizar el estado del itemplan');
                    }
                }
            }
         
            $arrayDataMotivo = array(
                                        'itemplan'          => $itemplan,
                                        'idMotivo'          => $idMotivoReplanteo,
                                        'comentario'        => $comentarioFluidTres,
                                        'idEstado'          => ID_ESTADO_ITEMPLAN_REPLANTEO,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession')
                                    );
            $data = $this->m_proceso_piloto->registrarProcesoPilotoMotivo($arrayDataMotivo);
            
            if($data['error'] == EXIT_ERROR) {
                throw new Exception('error al ingresar el motivo');
            } 
            
            $data['tablaBitacora'] = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_REPLANTEO);
            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaBitacora($itemplan, $idEstado) {
        $cont = 0;
        $arrayData = $this->m_proceso_piloto->getBitacoraMotivo($itemplan, $idEstado);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>MOTIVO</th>
                            <th>USUARIO</th>                            
                            <th>COMENTARIO</th>
                            <th>FECHA REGISTRO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($arrayData as $row){              
                    $cont++;
                    $html .=' <tr>
                                <td>'.$cont.'</td>
                                <td>'.utf8_decode($row['motivoDesc']).'</td>
                                <td>'.$row['usuarioDesc'].'</td>					
                                <td>'.$row['comentario'].'</td>
                                <td>'.$row['fechaRegistro'].'</td>
                            </tr>';
                }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function registrarFluidCuatro() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan              = $this->input->post('itemplan');
            $comentarioFluidCuatro = $this->input->post('comentarioFluidCuatro');
            $idMotivoElabFuit      = $this->input->post('idMotivoElabFuit');
            $duracion              = $this->input->post('duracionElabFuit');
            
            $d = new DateTime($duracion);
            $this->db->trans_begin();

            $duracionElabFuit = $d->format('H:i:s');

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            if($comentarioFluidCuatro == null) {
                throw new Exception('error, comentarioFluidTres null');
            }
            if($duracionElabFuit == null) {
                throw new Exception('error, duracionElabFuit null');
            }

            if($idMotivoElabFuit == ID_MOTIVO_EJECUCION_EN_PROCESO) {
                $file = $_FILES["file"]["name"];

                if(!$comentarioFluidCuatro) {
                    throw new Exception('error, subir archivo');
                }

                $file2 = utf8_decode($file);
                
                $ubicacion = 'uploads/elaboracion_fuit';
                if (!is_dir($ubicacion)) {
                    mkdir('uploads/elaboracion_fuit', 0777);
                }
                if (move_uploaded_file($_FILES['file']['tmp_name'], $ubicacion . "/" . $file2)) {
                    //log_message('error', 'subio el archivo');
                } else {
                    throw new Exception('ND');
                }
                _log($duracionElabFuit);
                $arrayData = array( 'id_config'                     => ID_CONFIG_ELABORACION_FUIT,
                                    'ubic_archivo_elaboracion_fuit' => $ubicacion. "/". $file2,
                                    'comentario_elaboracion_fuit'   => $comentarioFluidCuatro,
                                    'fecha_reg_elaboracion_fuit'    => $this->fechaActual(),
                                    'duracion_elaboracion_fuit'     => $duracionElabFuit);

                $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                } else {
                    $data = $this->m_proceso_piloto->updateEstadoItemplan($itemplan, ID_ESTADO_ITEMPLAN_CON_EXPEDIENTE, $this->fechaActual());  
            
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('error al actualizar el estado del itemplan');
                    }
                }                
            }

            $arrayDataMotivo = array(
                                        'itemplan'          => $itemplan,
                                        'idMotivo'          => $idMotivoElabFuit,
                                        'comentario'        => $comentarioFluidCuatro,
                                        'idEstado'          => ID_ESTADO_ITEMPLAN_CON_EXPEDIENTE,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession')
                                    );
            $data = $this->m_proceso_piloto->registrarProcesoPilotoMotivo($arrayDataMotivo);

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('ND');
            }

            $this->db->trans_commit();
            $data['tablaBitacora'] = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_CON_EXPEDIENTE);
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarFluidCinco() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan             = $this->input->post('itemplan');
            $comentarioFluidCinco = $this->input->post('comentarioFluidCinco');
            $idMotivoEntFuit      = $this->input->post('idMotivoEntFuit');
            $duracion             = $this->input->post('duracion');

            $d = new DateTime($duracion);
            $this->db->trans_begin();

            $duracionEntregaFuit = $d->format('H:i:s');

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            if($comentarioFluidCinco == null) {
                throw new Exception('error, comentarioFluidTres null');
            }
            if($idMotivoEntFuit == ID_MOTIVO_EJECUCION_EN_PROCESO) {
                $arrayData = array( 'id_config'               => ID_CONFIG_ENTREGA_FUIT,
                                    'comentario_entrega_fuit' => $comentarioFluidCinco,
                                    'duracion_entrega_fuit'   => $duracionEntregaFuit,
                                    'fecha_reg_entrega_fuit'  => $this->fechaActual());
                $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error al registrar el proceso');
                } else {
                
                    $data = $this->m_proceso_piloto->updateEstadoItemplan($itemplan, ID_ESTADO_ITEMPLAN_EXPEDIENTE_ENTREGADO, $this->fechaActual());  

                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('error al actualizar el estado del itemplan');
                    }
                }
            }

            $arrayDataMotivo = array(
                                        'itemplan'          => $itemplan,
                                        'idMotivo'          => $idMotivoEntFuit,
                                        'comentario'        => $comentarioFluidCinco,
                                        'idEstado'          => ID_ESTADO_ITEMPLAN_EXPEDIENTE_ENTREGADO,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession')
                                    );
            $data = $this->m_proceso_piloto->registrarProcesoPilotoMotivo($arrayDataMotivo);
            $data['tablaBitacora'] = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_EXPEDIENTE_ENTREGADO);
            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarFluidSeis() { //SOLOS SI EL MOTIVO FUE ANGENDA
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan            = $this->input->post('itemplan');
            // $fechaCitaAgenInst   = $this->input->post('fechaCitaFluidSeis');
            $comentarioFluidSeis = $this->input->post('comentarioFluidSeis');
            
            $this->db->trans_begin();

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            // if($fechaCitaAgenInst == null) {
            //     throw new Exception('error, fecha cita null');
            // }
            if($comentarioFluidSeis == null) {
                throw new Exception('error, comentarioFluidSeis null');
            }
            $arrayData = array('id_config'                   => ID_CONFIG_AGENDA_INSTALACION,
                            //    'fecha_cita_agen_instalacion' => $fechaCitaAgenInst,
                               'comentario_agen_instalacion' => $comentarioFluidSeis);
            $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('error al registrar el proceso');
            }
            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarFluidSiete() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan             = $this->input->post('itemplan');
            $comentarioFluidSiete = $this->input->post('comentarioFluidSiete');
            // $placa                = $this->input->post('placa');
            $idMotivoInsPex       = $this->input->post('idMotivoInsPex');
            $duracion             = $this->input->post('duracion');
            $cmbValeReserva = null;
            $d = new DateTime($duracion);
            $this->db->trans_begin();

            $duracionInsPex = $d->format('H:i:s');

            if($itemplan == null) {
                throw new Exception('error, itemplan null');
            }
            if($comentarioFluidSiete == null) {
                throw new Exception('error, comentarioFluidTres null');
            }

            if($idMotivoInsPex == ID_MOTIVO_EJECUCION_EN_PROCESO) {
                // if($placa == null) {
                //     throw new Exception('error, placa null');
                // }

                $arrayData = array( 'id_config'                  => ID_CONFIG_INSTALACION_PEX,
                                    'comentario_instalacion_pex' => $comentarioFluidSiete,
                                    'fecha_reg_instalacion_pex'  => $this->fechaActual(),
                                    'duracion_instalacion_pex'   => $duracionInsPex);
                $data = $this->m_proceso_piloto->updateProceso($itemplan, $arrayData, $this->fechaActual());

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error al registrar el proceso');
                } else {
                    $data = $this->m_proceso_piloto->updateEstadoItemplan($itemplan, ID_ESTADO_ITEMPLAN_PEX_EJECUTADO, $this->fechaActual());  
                    
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('error al actualizar el estado del itemplan');
                    } 
                    // else {
                    //     $arrayData = array( 'itemplan' => $itemplan, 
                    //                         // 'placa'    => $placa, 
                    //                         'estado'   => ESTADO_AUTO_OBRA_ACTIVO);
                    //     $data = $this->m_proceso_piloto->insertAutoObra($arrayData);

                    //     if($data['error'] == EXIT_ERROR) { 
                    //         throw new Exception($data['msj']);
                    //     }

                    //     $cmbValeReserva = __buildComboValeReserva($placa);
                    // } 
                }
            }

            $arrayDataMotivo = array(
                                        'itemplan'          => $itemplan,
                                        'idMotivo'          => $idMotivoInsPex,
                                        'comentario'        => $comentarioFluidSiete,
                                        'idEstado'          => ID_ESTADO_ITEMPLAN_PEX_EJECUTADO,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession')
                                    );
            $data = $this->m_proceso_piloto->registrarProcesoPilotoMotivo($arrayDataMotivo);

            $data['tablaBitacora']  = $this->getTablaBitacora($itemplan, ID_ESTADO_ITEMPLAN_PEX_EJECUTADO);
            $data['cmbValeReserva'] = $cmbValeReserva;
            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getInfoEntidades($itemplan) {
        //$itemplan     = $this->input->post('itemPlan') ? $this->input->post('itemPlan') : null;
        $flgProvincia = 1;
        $idEstacion = 21;
        // $idEstacion   = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
        // $flgProvincia = $this->input->post('flgProvincia') ? $this->input->post('flgProvincia') : null;

        if ($itemplan == null || $idEstacion == null || $flgProvincia == null) {
            throw new Exception('Hubo un error al traer entidades!!');
        }
        // log_message('error',' $flgProvincia: '. $flgProvincia);

        $tablaEntidades = $this->makeHTLMTablaIPEstaEnt($this->M_licencias->getItemPLanEstacionLincenciaDet($itemplan, $idEstacion), $flgProvincia);

        return $tablaEntidades;
    }

    

    public function makeHTLMTablaIPEstaEnt($listaEstacionEntidad, $flgProvincia) {

        if ($flgProvincia == 1) {
            $html = '
                <table id="tabla_entidades" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th style="text-align: center;"></th>
                            <th>ENTIDAD</th>
                            <th style="text-align: center">EXPEDIENTE</th>
                            <th style="text-align: center">TIPO</th>
                            <th>SUBIR/VER EVIDENCIA</th>
                            <th style="text-align: center">DISTRITO</th>
                            <th style="text-align: center">FEC. INICIO</th>
                            <th style="text-align: center">FEC. FIN</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
            $count = 1;
            $btnSubiEvi = '';
            $btnGuardar = '';
            $btnComprobante = '';
            $listaDistritos = $this->M_licencias->getAllDistritos();

            if ($listaEstacionEntidad != null) {
                foreach ($listaEstacionEntidad as $row) {

                    $htmlCmbTipoLic = $this->makeCmbTipoLic($row->iditemplan_estacion_licencia_det, $row->flg_validado, $row->flg_tipo);
                    $htmlCmbDistrito = $this->makeCmbDistrito($row->iditemplan_estacion_licencia_det, $row->flg_validado, $row->idDistrito, $row->flg_combo, $listaDistritos);

                    if ($row->flg_validado == 2 && $row->flg_tipo != 1 && $row->ruta_pdf != null) {
                        $btnSubiEvi = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                        $btnGuardar = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    } else {
                        $btnSubiEvi = '<a id="btnSaveIpEstDet" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  onclick="abrirModalEvidencia(this,1,null,null)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                        $btnGuardar = ' <a id="btnSaveIpEstDet" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" onclick="liquidarDetalle(this,' . $count . ')"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    }

                    if($row->flg_tipo != 1){
                        $btnComprobante = '<a  id="btnComprobante'.$row->iditemplan_estacion_licencia_det.'" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" onclick="abrirModalComprobantes(this)"><i class="zmdi zmdi-hc-2x zmdi-money"></i></a>';
                    }
                    
                    

                    $html .= '
                        <tr>
                            <td style="text-align:center">
                                ' . $btnComprobante . '
                            </td>
                            <td style="font-weight: bold;">' . $row->desc_entidad . '</td>
                            <td>
                                <input type="text" style="width: 80px;background:#FCF5A1" id="txtCodExp' . $row->iditemplan_estacion_licencia_det . '" maxlength="20" class="form-control" value="' . ($row->codigo_expediente ? $row->codigo_expediente : null) . '"   ' . ($row->flg_validado == 2 ? 'disabled' : '') . '>
                            </td>
                            <td>
                                ' . $htmlCmbTipoLic . '
                            </td>
                            <td style="text-align: center">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                       ' . $btnSubiEvi . '
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a style="color:var(--verde_telefonica);cursor:pointer" id="btnVerEviEnt' . $row->iditemplan_estacion_licencia_det . '" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  data-index="' . $count . '" onclick="descargarPDFEntidad(this,2,0)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                ' . $htmlCmbDistrito . '
                            </td>
                            <td>
                                <input type="date" style="background:#FCF5A1" id="txtFechaIni' . $row->iditemplan_estacion_licencia_det . '" class="form-control"  value="' . ($row->fecha_inicio ? $row->fecha_inicio : null) . '" ' . ($row->flg_validado == 2 ? 'disabled' : '') . '>
                            </td>
                            <td>
                                <input type="date" style="background:#FCF5A1" id="txtFechaFin' . $row->iditemplan_estacion_licencia_det . '" class="form-control"  value="' . ($row->fecha_fin ? $row->fecha_fin : null) . '" ' . ($row->flg_validado == 2 ? 'disabled' : '') . '>
                            </td>
                            <td style="text-align:center">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        ' . $btnGuardar . '
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a id="btnDeleteEnt" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" data-itemplan = "' . $row->itemPlan . '" data-idestacion = "' . $row->idEstacion . '"  onclick="deleteIPEstDetLic(this)"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a
                                    </div>
                                </div>
                            </td>
                        </tr>';
                        
                    $btnComprobante = '';

                    $count++;
                }
                $html .= '</tbody>
                </table>';

            } else {
                $html .= '</tbody>
                </table>';
            }
        } else {

            $html = '
                <table id="tabla_ent_prov" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th>ENTIDAD</th>
                            <th style="text-align: center" id="thCheque"># CHEQUE</th>
                            <th>ACOTACI&Oacute;N</th>
                            <th>LIQUIDAR</th>
                        </tr>
                    </thead>

                    <tbody>';
            $count = 1;

            if ($listaEstacionEntidad != null) {
                foreach ($listaEstacionEntidad as $row) {

                    $html .= '
                        <tr>
                            <td style="font-weight: bold;">' . $row->desc_entidad . '</td>
                            <td>
                                <input type="text" id="txtNroCheque' . $row->iditemplan_estacion_licencia_det . '" class="form-control" value="' . ($row->nro_cheque ? $row->nro_cheque : null) . '" style="display: ' . ($row->flg_acotacion_valida == null || $row->flg_acotacion_valida == 0 ? 'none' : 'block') . ' ">
                            </td>
                            <td>
                                <a id="btnModalAcota" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" ><i class="zmdi zmdi-hc-2x zmdi-assignment-o"></i></a>
                            </td>
                            <td>
                                <a id="btnAbrirModalEntProvxLiqui" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"><i class="zmdi zmdi-hc-2x zmdi-code-setting"></i></a>
                            </td>
                        </tr>';

                    $count++;
                }
                $html .= '</tbody>
                </table>';

            } else {
                $html .= '</tbody>
                </table>';
            }

        }

        return utf8_decode($html);
    }

    function makeCmbTipoLic($count, $flg_validado, $flg_tipo)
    {
        $selectedComu = ($flg_tipo == 1) ? 'selected' : null;
        $selectedLic = ($flg_tipo == 2) ? 'selected' : null;
        $selectedEIA = ($flg_tipo == 3) ? 'selected' : null;
        $selectedTip = ($flg_tipo == 0 || $flg_tipo == null) ? 'selected' : null;

        $html = '   <select class="form-control select2" id="tipoLic' . $count . '"  ' . ($flg_validado == 2 ? 'disabled' : '') . '  onchange="desactivaBtnCompro(' . $count . ')">
                        <option value="0" ' . $selectedTip . '  >Seleccionar Tipo</option>
                        <option value="1" ' . $selectedComu . ' >COMUNICATIVA</option>
                        <option value="2" ' . $selectedLic . '  >LICENCIA</option>
                        <option value="3" ' . $selectedEIA . '  >EIA</option>
                    </select>';

        return utf8_decode($html);
    }

    public function makeCmbDistrito($count, $flg_validado, $idDistrito, $flg_combo, $listaDistritos)
    {
        $html = '';

        if ($flg_combo == 1) {

            $html .= '<select class="form-control select2" id="distEnt' . $count . '"  ' . ($flg_validado == 2 ? 'disabled' : '') . '>
                        <option value="">Seleccionar Disitrito</option>';

            foreach ($listaDistritos as $row) {
                $selected = ($row->idDistrito == $idDistrito) ? 'selected' : null;
                $html .= '<option value="' . $row->idDistrito . '" ' . $selected . ' >' .utf8_encode($row->distritoDesc) . '</option>';
            }

            $html .= '</select>';
        }

        return utf8_decode($html);
    }

    function getCmbEntidades() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $dataEntidades = $this->cmbEntidades($this->M_licencias->getAllEntidades());
            $data['htmlEntidades'] = $dataEntidades['html'];
            if (isset($data['htmlEntidades'])) {
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function cmbEntidades($listaEntidades) {
        $html = '<option value="">Seleccionar Entidad</option>';

        foreach ($listaEntidades as $row) {
            $html .= '<option value="' . $row->idEntidad . '">' . $row->desc_entidad . '</option>';
        }
        $data['html'] = utf8_decode($html);
        return $data;
    }

    public function registrarEntidadesPiloto() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            // $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idPersonaSession = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $idEntidad = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;

            if ($idPersonaSession == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }
            if ($itemplan == null || $idEntidad == null) {
                throw new Exception('Hubo un error en traer los datos de registro!!');
            }
            
            $arrayInsert = array();
            array_push($arrayInsert,
                array(
                      'idEntidad'      => $idEntidad,
                      'idEstacion'     => 21,
                      'itemPlan'       => $itemplan,
                      'ruta_pdf'       => null,
                      'fecha_inicio'   => null,
                      'fecha_fin'      => null,
                      'id_usuario_reg' => $idPersonaSession,
                      'fecha_registro' => date("Y-m-d"),
                      'fecha_valida'   => null,
                      'flg_validado'   => 0,
                      'id_usuario_valida' => $idPersonaSession,
                    )
            );
            $data = $this->M_licencias->registrarEntidadesItemPlanEstaLic($arrayInsert);

            if ($data['error'] == EXIT_SUCCESS) {
                $data['tablaEntidades'] = $this->makeHTLMTablaIPEstaEnt($this->M_licencias->getItemPLanEstacionLincenciaDet($itemplan, 21), 1);
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function getModalComprobante() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle') ? $this->input->post('idItemPlanEstaDetalle') : null;

            if ($idItemPlanEstaDetalle == null) {
                throw new Exception('Hubo un error al recibir los datos!!');
            }
            $flgValidado = $this->M_licencias->getFlgValidadoByIdIPEstDet($idItemPlanEstaDetalle);

            if ($flgValidado != 0 && $flgValidado != null) {
                $listaComprobantes = $this->M_licencias->getComprobantesxItemPlanDet($idItemPlanEstaDetalle);

                $tablaComprobantes = $this->makeHTLMTablaComprobantes($this->M_licencias->getComprobantesxItemPlanDet($idItemPlanEstaDetalle));

                $data['tablaComprobantes'] = $tablaComprobantes;
                $data['error'] = EXIT_SUCCESS;
            } else {
                log_message('error', 'entro al else');
                $data['error'] = EXIT_ERROR;
                $data['msj'] = 'Debe registrar todo los datos de la licencia para poder registrar un comprobante!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function makeHTLMTablaComprobantes($listaComprobantes) {

        $html = '
                <table id="tabla_comprobantes" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th style="text-align: center;"># COMPROBANTE</th>
                            <th style="text-align: center;">FECHA DE EMISI&Oacute;N</th>
                            <th style="text-align: center">MONTO(S/)</th>
                            <th>SUBIR/VER COMPROBANTE</th>
                            <th style="text-align: center">ESTADO</th>
                            <th style="text-align: center">VALIDA COMPROBANTE</th>
                            <th style="text-align: center">PRELIQUI ADMINISTRATIVA</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;
        $btnGuardar = '';

        if ($listaComprobantes != null) {
            foreach ($listaComprobantes as $row) {

                if ($row->estado_valida == 2) {
                    $btnGuardar = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    $btnSubiEvi = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                } else {
                    $btnGuardar = '  <a id="btnSaveComprobante' . $row->idReembolso . '"  data-idreembolso="' . $row->idReembolso . '"  data-rutapdf="' . $row->ruta_foto . '" style="color: var(--verde_telefonica); cursor: pointer"  onclick="saveComprobante(this,2)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    $btnSubiEvi = '<a id="btnSubirEviCompro" style="color: var(--verde_telefonica); cursor: pointer" onclick="abrirModalEvidencia(null,2,1)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                }

                $html .= ' <tr>
                                <td>
                                    <input style="background:#FCF5A1" type="text" id="txtDescCompro' . $row->idReembolso . '" class="form-control" value="' . ($row->desc_reembolso ? $row->desc_reembolso : null) . '" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '>
                                </td>
                                <td>
                                    <input style="background:#FCF5A1" type="date" id="txtFechaEmiCompro' . $row->idReembolso . '" class="form-control" value="' . ($row->fecha_emision ? $row->fecha_emision : null) . '" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '>
                                </td>
                                <td>
                                    <input style="background:#FCF5A1" type="number" id="txtMontoCompro' . $row->idReembolso . '" class="form-control" value="' . ($row->monto ? $row->monto : null) . '" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '>
                                </td>
                                <td style="text-align:center">

                                    <div class="row">
                                        <div class="col-sm-6 col-md-5">
                                            ' . $btnSubiEvi . '
                                        </div>
                                        <div class="col-sm-6 col-md-5">
                                            <a style="color: var(--verde_telefonica);cursor: pointer" id="btnVerEviCompro" data-idreembolso="' . $row->idReembolso . '" onclick="descargarPDFCompro(this,2,0)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                        </div>
                                    </div>

                                </td>
                                <td>' . ($row->estado_valida == 1 ? 'ATENDIDO' : ($row->estado_valida == 2 ? 'PRELIQUIDADO' : 'PENDIENTE')) . '</td>
                                <td style="text-align:center">
                                    <input ype="checkbox" id="chkValidaCompro' . $row->idReembolso . '"  onchange="validaCompro(this,' . $row->idReembolso . ')" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '  style="display:  ' . ($row->flg_preliqui_admin == '1' ? 'none' : 'block') . '"  ' . ($row->flg_valida_evidencia == '1' ? 'checked' : '') . '>
                                </td>
                                <td style="text-align:center">
                                    <input  id="chkxPreLiquiAd' . $row->idReembolso . '" type="checkbox"  onchange="preliqAdmin(this,' . $row->idReembolso . ')" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '  style="display:  ' . ($row->flg_valida_evidencia == '1' ? 'none' : 'block') . '" ' . ($row->flg_preliqui_admin == '1' ? 'checked' : '') . '>
                                </td>
                                <td style="text-align:center">
                                   ' . $btnGuardar . '
                                </td>
                           </tr>';

                $count++;
            }

        } else {

            $html .= ' <tr>
                            <td>
                                <input  style="background:#FCF5A1" type="text" id="txtDescCompro" class="form-control">
                            </td>
                            <td>
                                <input style="background:#FCF5A1" type="date" id="txtFechaEmiCompro" class="form-control">
                            </td>
                            <td>
                                <input style="background:#FCF5A1" type="number" id="txtMontoCompro" class="form-control">
                            </td>
                            <td style="text-align:center">

                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        <a id="btnSubirEviCompro" style="color: var(--verde_telefonica); cursor: pointer" onclick="abrirModalEvidencia(null,2,1)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a style="color:#9c9c63;" id="btnVerEviCompro"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                    </div>
                                </div>

                            </td>
                            <td>

                            </td>
                            <td style="text-align:center">
                                <input  style="background:#FCF5A1" type="checkbox" id="chkValidaCompro"  onchange="validaCompro(this,null)">
                            </td>
                            <td style="text-align:center">
                                <input style="background:#FCF5A1"  type="checkbox" id="chkxPreLiquiAd"  onchange="preliqAdmin(this,null)">
                            </td>
                            <td style="text-align:center">
                                <a id="btnSaveComprobante" style="color: var(--verde_telefonica); cursor: pointer"  onclick="saveComprobante(this,1)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>
                            </td>
                        </tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function getTablaKitMaterial($itemplan, $placa) {
        $cont = 0;
        $disabled = null;
        $arrayData = $this->m_utils->getMaterialAuto($itemplan, $placa);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>MATERIAL</th>
                            <th>COSTO</th>
                            <th>CANTIDAD</th>
                            <th>TOTAL</th>                            
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($arrayData as $row){
                    if($row['cantidad_final']) {
                        $disabled = 'disabled';
                    }
                              
                    $cont++;
                    $html .='<tr>
                                <td>'.$cont.'</td>
                                <td>'.utf8_decode($row['descrip_material']).'</td>
                                <td>'.$row['costo_material'].'</td>			
                                <td><input id="inputCantidad_'.$cont.'" type="text" class="form-control" value="'.$row['cantidad_final'].'"
                                    onchange="getDataInsert('.$row['id_material'].','.$cont.','.$row['costo_material'].');" '.$disabled.'></td>                          		                        
                                <td><input id="inputTotal_'.$cont.'" type="text" value="'.$row['costoMat'].'" class="form-control" disabled/></td>
                             </tr>';
                }
            $html .='</tbody>
                </table>';
                    
        return $html;
    }

    function insertInfoPOPiloto() {
        $idUsuario = $this->session->userdata('idPersonaSession');

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            
            $itemplan       = $this->input->post('itemplan');
            $arrayDetalle   = $this->input->post('arrayDetalleKit');
            $totalPo        = $this->input->post('totalPo');
            $idEstacion     = 5;

            if($itemplan == null) {
                throw new Exception('error Itemplan NULL, Comunicarse con el programador');
            }
            
            if($idEstacion == null) {
                throw new Exception('error Estacion NULL, Comunicarse con el programador');
            }

            if($totalPo == null) {
                throw new Exception('error Total Null, Comunicarse con el programador');                
            }

            // $estado     = $this->m_carga_masiva_itemplan->aprobEstadoAuto($itemplan);

            
            // if($estado == null OR $estado == '') {
            //     throw new Exception('error Estado Comunicarse con el programador');
            // }

            $arrayPlanPO      = array();
            $arrayDetallePlan = array();
            $arrayLogPO       = array();
            $arrayDetallePO   = array();
            $arrayTmpUpdateFlgIngreso = array();
            // $arrayData = $this->m_carga_masiva_itemplan->getDataInsertPO($itemplan, $idUsuario, $idEstacion);
            $cont = 0;
            $flgInsert = 0;
            $po = null;

                //$countCodPO = $this->m_utils->countPOByItemplanAndEstacion($itemplan, $row->idEstacion, FROM_DISENIO);
                // if($countCodPO > 0) {
                //     throw new Exception('Este itemplan ya tiene una PO en esta estaci&oacute;n');
                // }
            $cont++;
            $idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, 'MAT');

            if($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                throw new Exception('error id SubProyecto Estacion null');
            }

            $po = $this->m_utils->getCodigoPO($itemplan);

            if($po == null || $po == '') {
                throw new Exception('error PO null');
            }

            $jsonArrayData['itemplan']      = $itemplan;
            $jsonArrayData['codigo_po']     = $po; 
            $jsonArrayData['estado_po']     = 1; 
            $jsonArrayData['idEstacion']    = $idEstacion;
            $jsonArrayData['from']          = FROM_PILOTO;
            $jsonArrayData['idUsuario']     = $idUsuario; 
            $jsonArrayData['fechaRegistro'] = $this->fechaActual();
            $jsonArrayData['costo_total']   = $totalPo;
            $jsonArrayData['flg_tipo_area'] = 1;
            $jsonArrayData['id_eecc_reg']   = 1;

            array_push($arrayPlanPO, $jsonArrayData);

            $jsonArrayDetallePlan['idSubProyectoEstacion'] = $idSubProyectoEstacion;
            $jsonArrayDetallePlan['poCod']                 = $po;
            $jsonArrayDetallePlan['itemPlan']              = $itemplan;
            array_push($arrayDetallePlan, $jsonArrayDetallePlan);

            $jsonLog['itemplan']       = $itemplan;
            $jsonLog['codigo_po']      = $po;
            $jsonLog['idUsuario']      = $idUsuario; 
            $jsonLog['fecha_registro'] = $this->fechaActual();
            $jsonLog['idPoestado']     = 1; 
            $jsonLog['controlador']    = 'C_CARGA_MASIVA_ITEMPLAN';

            array_push($arrayLogPO, $jsonLog);

            if($po == null || $po == '') {
                throw new Exception('debe ingresar materiales no bucles');
            }
            $flgInsert = 1;
            foreach($arrayDetalle as $row) {
                unset($row['costoMaterial']);
                $row['codigo_po'] = $po;

                array_push($arrayDetallePO, $row);
            }

           $data = $this->m_proceso_piloto->insertPOPiloto($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO, $arrayLogPO); 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getAgendaCalendarProcPiloto() {
        $arrayDataAgen = $this->m_proceso_piloto->getAgendamiento(NULL);
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

    function getDetalleAgByFechaProcPiloto() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $fecha = ($this->input->post('fecha') == null ? date('Y-m-d') : date('Y-m-d', ($this->input->post('fecha') / 1000) ) );

            if($fecha == null) {
                throw new Exception("ND fecha");
            }
            $data['error'] = EXIT_SUCCESS;
            $arrayDataAgen = $this->m_proceso_piloto->getAgendamiento($fecha);
            $tablaModalDetalleAgen = $this->getTablaDetalleAgendamiento($arrayDataAgen);
            $data['tablaDetalleAgenda'] = $tablaModalDetalleAgen;
            $data['fechaAgen'] = $fecha;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaDetalleAgendamiento($arrayDataAgen) {
        $html = '<table id="tbDetalleAgenda" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white ; background-color: #3b5998">Itemplan</th>
                            <th style="color: white ; background-color: #3b5998">Jefatura</th>  
                            <th style="color: white ; background-color: #3b5998">EECC</th>
                            <th style="color: white ; background-color: #3b5998">Banda Horaria</th>
                            <th style="color: white ; background-color: #3b5998">Estado</th>                            
                            <th style="color: white ; background-color: #3b5998">Usuario Registro</th>
                            <th style="color: white ; background-color: #3b5998">Fecha Registro</th>                                                                             
                        </tr>
                    </thead>                    
                    <tbody>';

        foreach($arrayDataAgen as $row) {

        $html .='   <tr>
                        <td style="color: white ; background-color: #3b5998">'.$row['itemplan'].'</td>
                        <td>'.$row['jefatura'].'</td>
                        <td>'.$row['empresaColabDesc'].'</td>							
                        <th>'.$row['bandaHoraria'].'</th>
                        <th>'.$row['estado'].'</th>	
                        <th>'.$row['usuarioRegistro'].'</th>		
                        <th>'.$row['fecha_registro'].'</th>			                                                    				                        
                    </tr>';
        }
        $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function getTablaAgenda() {
        $html = '<table id="tbAgenda" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white ; background-color: #3b5998">Itemplan</th>
                            <th style="color: white ; background-color: #3b5998">Jefatura</th>  
                            <th style="color: white ; background-color: #3b5998">EECC</th>
                            <th style="color: white ; background-color: #3b5998">Banda Horaria</th>
                            <th style="color: white ; background-color: #3b5998">Estado</th>                            
                            <th style="color: white ; background-color: #3b5998">Usuario Registro</th>
                            <th style="color: white ; background-color: #3b5998">Fecha Registro</th>                                                                             
                        </tr>
                    </thead>                    
                    <tbody>';

        foreach($arrayDataAgen as $row) {

        $html .='   <tr>
                        <td style="color: white ; background-color: #3b5998">'.$row['itemplan'].'</td>
                        <td>'.$row['jefatura'].'</td>
                        <td>'.$row['empresaColabDesc'].'</td>							
                        <th>'.$row['bandaHoraria'].'</th>
                        <th>'.$row['estado'].'</th>	
                        <th>'.$row['usuarioRegistro'].'</th>		
                        <th>'.$row['fecha_registro'].'</th>			                                                    				                        
                    </tr>';
        }
        $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}