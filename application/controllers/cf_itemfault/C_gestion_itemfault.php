<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 *
 */
class C_gestion_itemfault extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        //$this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_pqt_plan_obra/m_pqt_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_log/m_log_ingfix');
        $this->load->model('mf_pqt_plan_obra/m_control_estado_itemplan');
        $this->load->model('mf_itemfault/m_gestion_itemfault');
        $this->load->model('mf_itemfault/m_consulta');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $est = (isset($_GET['est']) ? $_GET['est'] : '');

            $data['item'] = $item;
            $data['est'] = $est;

            //$data['estadoItemplan'] = $this->m_detalle_obra->getItemEstado($item);

            $data['htmlBarraProgreso'] = $this->makeBarraEstadoItemPlan($item, $est);


            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_itemfault/v_gestion_itemfault', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeBarraEstadoItemPlan($itemplan, $est) {
        //SI ESTA SUSPENDIDO, CANCELADO O TRUNCO, SE DEBE DE OBTENER EL ESTADO ANTERIOR
        $estadoRegularItemPlan = $est;
        $flagEstadoCambiado = "1";

        $listControlEstadoPlan = $this->m_gestion_itemfault->getLastEstadoByItemPlan($itemplan);
        foreach ($listControlEstadoPlan->result() as $row) {
            if ($est == ID_ESTADO_SUSPENDIDO) {
                $estadoRegularItemPlan = $row->idEstadoPlanAnt;
                $flagEstadoCambiado = "2";
            } else if ($est == ID_ESTADO_TRUNCO) {
                $estadoRegularItemPlan = $row->idEstadoPlanAnt;
                $flagEstadoCambiado = "4";
            } else if ($est == ID_ESTADO_CANCELADO) {
                $estadoRegularItemPlan = $row->idEstadoPlanAnt;
                $flagEstadoCambiado = "3";
            } else {
                if ($row->has_paralizado == 1) {#si esta paralizado es como si estuviera suspendido
                    #$estadoRegularItemPlan = $row->idEstadoPlanAnt;
                    $flagEstadoCambiado = "2";
                }
            }
        }

        /*
         * 1 = Mantiene el estado original
         * 2 = Estado Suspendido -- Color AMARILLO -- 18
         * 3 = Estado Cancelado  -- Color ROJO     -- 6
         * 4 = Estado Trunco     -- Color NARANJA  -- 10
         * */
        //RECORRER EL ARRAY DE ESTADOS CORRECTOS

        /*
          1	Pre Dise�o
          2	Dise�o
          19  En Licencia
          20  En Aprobacion
          3	En Obra
          9	Pre Liquidado
          21  En Validacion
          4	Terminado
          22  En Certificacion
          23  Certificado
         */
        $j = 0;

        $rowPreDiseno = "";
        $rowDiseno = "";
        $rowLicencia = "";
        $rowAprobacion = "";
        $rowEnObra = "";
        $rowPreLiquidado = "";
        $rowEnValidacion = "";
        $rowTerminado = "";
        $rowEnCertificacion = "";
        $rowCertificado = "";

        $stylePointer = '';
        $accion = '';
        $class = '';

        //PRE DISE�O
        if ($estadoRegularItemPlan == ID_ESTADO_PRE_DISENIO) {
            $stylePointer = '';
            $accion = '';
            $class = '';
            $paralizado = 'Pre Dise&ntilde;o';
            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $listaBandejaAdjudicacion = $this->m_pqt_consulta->getBandejaDeAdjudicacionXItemPlan($itemplan);
                foreach ($listaBandejaAdjudicacion->result() as $row2) {
                    $countParalizados = $this->m_utils->countParalizados($row2->itemplan, FLG_ACTIVO, ORIGEN_WEB_PO);
                    $accion = 'onclick="cargarPreDiseno(\'' . $row2->itemplan . '\',\'' . $row2->coaxial . '\',\'' . $row2->fo . '\')" ';
                    $stylePointer = 'cursor: pointer;';
                    if ($countParalizados > 0) {
                        $accion = '';
                        $paralizado = 'Paralizado';
                        $class = 'c_cancelado';
                    }
                }
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowPreDiseno = '<div id="PreDiseno" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">' . $paralizado . '</p></div>';

            $j++;
        } else {
            $rowPreDiseno = '<div id="PreDiseno" class="c_culminado" style="line-height: 85px;"><p class="text-white">Pre Registro</p></div>';
        }

        //DISE�O
        if ($estadoRegularItemPlan == ID_ESTADO_DISENIO) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarDiseno(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowDiseno = '<div id="Diseno" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">Dise&ntilde;o</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowDiseno = '<div id="Diseno" class="c_pendiente" style="line-height: 85px;"><p class="text-white">Dise&ntilde;o</p></div>';
            } else {
                $accion = ' onclick="cargarDiseno(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
                $rowDiseno = '<div id="Diseno" class="c_culminado" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">Dise&ntilde;o</p></div>';
            }
        }

        //EN APROBACION
        if ($estadoRegularItemPlan == ID_ESTADO_EN_APROBACION) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarEnAprobacion(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowAprobacion = '<div id="En Aprobacion" class="' . $class . '" style="line-height: 35px;' . $stylePointer . '" ' . $accion . '><p class="text-white">En Aprobaci&oacute;n</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowAprobacion = '<div id="En Aprobacion" class="c_pendiente" style="line-height: 35px;"><p class="text-white">En Aprobaci&oacute;n</p></div>';
            } else {
                $accion = ' onclick="cargarEnAprobacion(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
                $rowAprobacion = '<div id="En Aprobacion" class="c_culminado" style="line-height: 35px;' . $stylePointer . '" ' . $accion . '><p class="text-white">En Aprobaci&oacute;n</p></div>';
            }
        }

        //EN OBRA
        if ($estadoRegularItemPlan == ID_ESTADO_PLAN_EN_OBRA) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_siom';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarEnObra(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowEnObra = '<div id="En Obra" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">En Obra</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowEnObra = '<div id="En Obra" class="c_pendiente" style="line-height: 85px;"><p class="text-white">En Obra</p></div>';
            } else {
                //Culminado
                $class = 'c_siom';
                $accion = ' onclick="cargarEnObra(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
                $rowEnObra = '<div id="En Obra" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">En Obra</p></div>';
            }
        }

        //PRE LIQUIDADO
        if ($estadoRegularItemPlan == ID_ESTADO_PLAN_EN_OBRA) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarPreLiquidado(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowPreLiquidado = '<div id="Pre Liquidado/Trunco" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">Pre Liquidado</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowPreLiquidado = '<div id="Pre Liquidado/Trunco" class="c_pendiente" style="line-height: 85px;"><p class="text-white">Pre Liquidado</p></div>';
            } else {

                $class = 'c_culminado';
                $accion = ' onclick="cargarPreLiquidado(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
                $rowPreLiquidado = '<div id="Pre Liquidado/Trunco" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">Pre Liquidado</p></div>';
            }
        }

        //EN VALIDACION
        if ($estadoRegularItemPlan == ID_ESTADO_EN_VALIDACION || $estadoRegularItemPlan == ID_ESTADO_PRE_LIQUIDADO) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarEnValidacion(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowEnValidacion = '<div id="En Validacion" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">En Validaci&oacute;n</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowEnValidacion = '<div id="En Validacion" class="c_pendiente" style="line-height: 85px;"><p class="text-white">En Validaci&oacute;n</p></div>';
            } else {
                $rowEnValidacion = '<div id="En Validacion" class="c_culminado" style="line-height: 85px;"><p class="text-white">En Validaci&oacute;n</p></div>';
            }
        }

        //TERMINADO
        if ($estadoRegularItemPlan == ID_ESTADO_TERMINADO) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarTerminado(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowTerminado = '<div id="Terminado" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">Terminado</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowTerminado = '<div id="Terminado" class="c_pendiente" style="line-height: 85px;"><p class="text-white">Terminado</p></div>';
            } else {
                $rowTerminado = '<div id="Terminado" class="c_culminado" style="line-height: 85px;"><p class="text-white">Terminado</p></div>';
            }
        }

        //EN CERTIFICACION
        if ($estadoRegularItemPlan == ID_ESTADO_EN_CERTIFICACION) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarEnCertificacion(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowEnCertificacion = '<div id="En Certificacion" class="' . $class . '" style="line-height: 35px;' . $stylePointer . '" ' . $accion . '><p class="text-white">En Certificaci&oacute;n</p></div>';
            $j++;
        } else {
            if ($j > 0) {
                $rowEnCertificacion = '<div id="En Certificacion" class="c_pendiente" style="line-height: 35px;"><p class="text-white">En Certificaci&oacute;n</p></div>';
            } else {
                $rowEnCertificacion = '<div id="En Certificacion" class="c_culminado" style="line-height: 35px;"><p class="text-white">En Certificaci&oacute;n</p></div>';
            }
        }

        //CERTIFICADO
        if ($estadoRegularItemPlan == 23) {
            $stylePointer = '';
            $accion = '';
            $class = '';

            if ($flagEstadoCambiado == 1) {
                $class = 'c_actual';
                $perfilUsuario = $this->session->userdata('idPerfilSession');
                //ALGUNA LOGICA PARA ACTIVAR BOTON VALIDANDO SI EL PERFIL PUEDE DAR CLICK
                $accion = ' onclick="cargarCertificado(\'' . $itemplan . '\')" ';
                $stylePointer = 'cursor: pointer;';
            } else {
                $accion = '';
                $stylePointer = '';
                if ($flagEstadoCambiado == 2) {
                    $class = 'c_suspendido';
                } else if ($flagEstadoCambiado == 3) {
                    $class = 'c_cancelado';
                } else if ($flagEstadoCambiado == 4) {
                    $class = 'c_trunco';
                }
            }

            $rowCertificado = '<div id="Certificado" class="' . $class . '" style="line-height: 85px;' . $stylePointer . '" ' . $accion . '><p class="text-white">Certificado</p></div>';
        } else {
            if ($j > 0) {
                $rowCertificado = '<div id="Certificado" class="c_pendiente" style="line-height: 85px;"><p class="text-white">Certificado</p></div>';
            } else {
                $rowCertificado = '<div id="Certificado" class="c_culminado" style="line-height: 85px;"><p class="text-white">Certificado</p></div>';
            }
        }

        $html_div = '<br><br>
                            <div class="container">
                            <div class="row justify-content-md-center no-gutters">
                            <div class="col-md-auto">
                            ' . $rowPreDiseno . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowDiseno . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowLicencia . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowAprobacion . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowEnObra . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowPreLiquidado . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowEnValidacion . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowTerminado . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowEnCertificacion . '
                            </div>
                            <div class="col-md-auto">
                            <div class="cuadrado"></div>
                            </div>
                            <div class="col-md-auto">
                            ' . $rowCertificado . '
                            </div>
                            </div>
                            </div>
                            <br>';
        return utf8_decode($html_div);
    }

    function getDataDisenoItemfault() {
        $itemfault = $this->input->post('itemplan');
        $data['tablaDiseno'] = $this->getTablaDiseno($itemfault);

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaDiseno($itemfault) {
        $dataDiseno = $this->m_gestion_itemfault->getDataDiseno($itemfault, 2);
        $html = null;
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>APROBAR</th>
                            <th>ITEMFAULT</th>
                            <th>ESTACION</th>
                            <th>NOMBRE DE URA</th>
                            <th>SERVICIO DE RED</th>
                            <th>ELEMENTO DE RED DE SERVICIO</th>
                            <th>EECC</th>
                            <th>FECHA DE CREACION</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead                   
                    <tbody>';

        foreach ($dataDiseno as $row) {
            $rowValidPo = $this->m_gestion_itemfault->getValidaPoMatMo($row->itemfault, $row->idEstacion);
            $btnCheck = null;
            if ($rowValidPo['flg_mo'] > 0 && $rowValidPo['flg_mat'] > 0) {
                $btnCheck = '<a data-item="' . $row->itemfault . '" data-id_estacion="' . $row->idEstacion . '" onclick="abrirModalEjecutarDis(this)" style="margin-left: 10%;"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/check_24016.png"></a>';
            }

            $html .= '<tr id="' . $row->itemfault . $row->idEstacion . '"> 
                        <th>' . $btnCheck . '</th>
                        <th>' . (($row->idEstadoPlan == ESTADO_PLAN_DISENO) ? '<a href="#"  class="ver_ptr" data-idrow="' . $row->itemfault . $row->idEstacion . '" data-estacion="' . $row->idEstacion . '">' . $row->itemfault . '</a>' : $row->itemfault ) . '</th>	
                        <th>' . $row->estacionDesc . '</th>                             
                        <th>' . $row->nombre . '</th>							
                        <th>' . $row->servicioDesc . '</th>
                        <th>' . $row->elementoDesc . '</th>
                        <th>' . $row->eecc . '</th>						
                        <th>' . $row->fecha_registro . '</th>
                        <th>' . $row->estadoPlanDesc . '</th>
                                                 
                    </tr>';
        }

        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function ejecutarDisenoItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $comentario = $this->input->post('comentario');
            $itemfault = $this->input->post('itemfault');
            $idEstacion = $this->input->post('idEstacion');

            $file = $_FILES ["archivoExpediente"] ["name"];
            $filetype = $_FILES ["archivoExpediente"] ["type"];
            $filesize = $_FILES ["archivoExpediente"] ["size"];

            $ubicacion = 'uploads/itemfault/diseno/' . $itemfault . '_' . $idEstacion;
            $idUsuario = $this->session->userdata('idPersonaSession');

            if ($itemfault == null || $itemfault == '') {
                throw new Exception('error al no encontrar itemfault');
            }

            if ($idEstacion == null || $idEstacion == '') {
                throw new Exception('error al no encontrar estacion');
            }

            if (!is_dir($ubicacion)) {
                mkdir('uploads/itemfault/diseno/' . $itemfault . '_' . $idEstacion, 0777);
            }
            $uploadfile1 = $ubicacion . '/' . basename($_FILES['archivoExpediente']['name']);

            $file2 = utf8_decode($file);

            if (move_uploaded_file($_FILES['archivoExpediente']['tmp_name'], $uploadfile1)) {
                $dataInsert = array(
                    'itemfault' => $itemfault,
                    'comentario' => $comentario,
                    'idEstacion' => $idEstacion,
                    'ubic_doc' => $ubicacion,
                    'fecha_registro' => $this->fechaActual(),
                    'id_usuario' => $idUsuario
                );

                $dataUpdate = array('idEstadoItemfault' => 20);

                $data = $this->m_gestion_itemfault->updateEjecDiseno($dataInsert, $dataUpdate, $itemfault);
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception('Error al actualizar la ruta del archivo.');
                }
            } else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    /*     * czavalacas********************** */

    function getDataEnAprobacionItemFault() {
        $itemfault = $this->input->post('itemplan');
        $data['tablaPreAprob'] = $this->getTablaPreAprobacion($itemfault);

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaPreAprobacion($itemplan) {
        $dataConsulta = $this->m_consulta->getTablaBandejaPreAproByItemplan($itemplan);
        $html = '';
        $accion = '';
        if (count($dataConsulta) == 0) {
            $html .= '<table id="tbDataConsulta" class="table table-bordered  table-responsive" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th></th>
                                                        <th>ITEMFAULT</th>
                                                        <th>PO</th>
														<th>COSTO PO</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
                                                        <th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbDataConsulta" class="table table-bordered  table-responsive" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th></th>
                                                        <th>ITEMFAULT</th>
                                                        <th>PO</th>
														<th>COSTO PO</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
														<th>CUENTA OPEX</th>
														<th>CUENTA CECO</th>
														<th>AREA FUNCIONAL</th>
                                                        <th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';
            foreach ($dataConsulta as $row) {
                $html .= '<tr>
                            <td>' . $accion . '</td>
                            <td>' . $row->itemfault . '</td>
                            <td>' . $row->codigo_itemfault_po . '</td>
							<td>' . $row->costo_total . '</td>
                            <td>' . $row->nombre . '</td>
                            <td>' . $row->servicioDesc . '</td>
                            <td>' . $row->elementoDesc . '</td>
							<td>' . $row->cuenta_opex . '</td>
							<td>' . $row->cuenta_seco . '</td>
							<td>' . $row->area_funcional . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->estadoPlanDesc . '</td>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

    /*     * **************************************** */
}
