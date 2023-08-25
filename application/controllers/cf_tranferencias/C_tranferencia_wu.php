<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_tranferencia_wu extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tranferencias/m_tranferencia_wu');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_extractor/m_extractor');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_TRANFERENCIA_WU);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_tranferencias/v_tranferencia_wu', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }

    }

    public function upload1()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            log_message('error', 'entro al metodo upload1');
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            log_message('error', 'entro al upload1');
            $uploaddir = 'uploads/wu/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
			
            //dirname(__FILE__);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				log_message('error', '::::BASEPATH::::>'.FCPATH);
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);
                if (count($comp) == NUM_COLUM_TXT_WEB_UNI) {
					//log_message('error', '==numcol' . NUM_COLUM_TXT_WEB_UNI);
					//chmod($uploadfile,0777);
                    $this->session->set_flashdata('rutaFileWu', $uploadfile);
                    $arrayInsertLogWU = array(
                        "descripcion" => 'termino de cargar upload1',
                        "fecha_registro" => $this->fechaActual(),
                    );

                    $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                    $data['error'] = EXIT_SUCCESS;
                } else {log_message('error', 'up1_else');
                    throw new Exception('El archivo no cuenta con la estructura correcta (35 columnas separados por tabulaciones.)');
                }

            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function upload2()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
			
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
			//log_message('error', 'rutaFileWu: ' . print_r($this->session->flashdata('rutaFileWu'), true));
            $data = $this->m_tranferencia_wu->getWebUnificadaFa($this->session->flashdata('rutaFileWu'));
            //log_message('error', 'rutaFileWu: ' . print_r($this->session->flashdata('rutaFileWu'), true));
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception("ERROR CARGA getWebUnificadaFa UPLOAD 2");
            }
            $data = $this->m_tranferencia_wu->loadWebUnificada();
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception("ERROR CARGA loadWebUnificada UPLOAD 2");
            }
             $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar upload2',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function upload3()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $data = $this->m_tranferencia_wu->execLoadWebUnificada();
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogWU = array(
                    "descripcion" => 'termino de cargar upload3',
                    "fecha_registro" => $this->fechaActual(),
                );

                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload3)';
        }
        echo json_encode($data);
    }

    public function upload4()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $data = $this->m_tranferencia_wu->execGetUpdateExternos();
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogWU = array(
                    "descripcion" => 'termino de cargar upload4',
                    "fecha_registro" => $this->fechaActual(),
                );

                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
	
                $this->m_utils->generarPOLicenciaFinalizacion(1493);          
                
                $this->m_utils->generarPOLicenciaGestion(NULL, 1493, NULL);
                
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload4)';
        }
        echo json_encode($data);
    }

    public function upload5()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $data = $this->m_tranferencia_wu->execAutoAprobSirope();
            if ($data['error'] == EXIT_SUCCESS) {
                $data = $this->m_tranferencia_wu->execAutoAprobMATBySubPro();
                if ($data['error'] == EXIT_SUCCESS) {
                    $data = $this->m_tranferencia_wu->execGetGrafos();
                          $arrayInsertLogWU = array(
                        "descripcion" => 'termino de cargar upload5',
                        "fecha_registro" => $this->fechaActual(),
                    );
    
                    $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception('Error en execGetGrafos()');
                    }
                }else{
                    throw new Exception('Error en execAutoAprobMATBySubPro()');
                }
            }else{
                throw new Exception('Error en execAutoAprobSirope()');
            }
        } catch (Exception $e) {
           $data['msj'] = 'Error interno, comuniquese con el administrador (Upload5).'.$e->getMessage();
        }
        echo json_encode($data);
    }

    public function upload8()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $data = $this->m_tranferencia_wu->execLoadCertificacion();
             if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogWU = array(
                    "descripcion" => 'termino de cargar upload8',
                    "fecha_registro" => $this->fechaActual(),
                );

                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                if ($data['error'] == EXIT_SUCCESS) {
                    $data = $this->m_tranferencia_wu->execLoadCertificacionMO();
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLogWU = array(
                            "descripcion" => 'termino de cargar upload8 MO',
                            "fecha_registro" => $this->fechaActual(),
                        );                    
                        $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                    }
                }else{
                    throw new Exception("Error al insertar log  loadCertificacion");
                }
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (upload8)';
        }
        echo json_encode($data);
    }

    public function upload6()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $data = $this->m_tranferencia_wu->execGetEstMate();
             if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogWU = array(
                    "descripcion" => 'termino de cargar upload6',
                    "fecha_registro" => $this->fechaActual(),
                );

                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload6)';
        }
        echo json_encode($data);
    }

    public function upload7()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $idUsuario = $this->session->userdata('idPersonaSession');
            log_message("error", 'inicio execGetEstMO()');
            log_message("error", 'inicio idUsuario: '.$idUsuario);
            $data = $this->m_tranferencia_wu->execGetEstMO();
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error en execGetEstMO()');
            } else if ($data['error'] == EXIT_SUCCESS) {
                log_message("error", 'inicio creacion reportes');
                //$this->crearCSVDetallePlan();
                /********************************CREACION ARCHIVO VALE RESERVA MIGUEL RIOS 14052018*********************/
                $this->crearCSVItemValeReserva();
                /******************************************************************************************************/
                /*********CREACION ARCHIVO CERTIFICACION 04072018************/
                log_message("error", 'inicio CVS Certificacion');
                $this->crearCVSCertificacion();
                /*************************************/
                log_message("error", 'inicio actualizacion toro');
                $data = $this->m_utils->execActualizaToro();

                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception('Error en execActualizaToro()');
                } else if ($data['error'] == EXIT_SUCCESS) {
                    log_message("error", 'inicio gerenar log');
                    $fechaActual = $this->m_utils->fechaActual();
                    $data = $this->m_tranferencia_wu->saveLogGenerarDP($fechaActual);
                    log_message("error", 'termino gerenar log');
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLogWU = array(
                            "descripcion" => 'termino de cargar upload7',
                            "fecha_registro" => $this->fechaActual(),
                        );

                        $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                        $data['error'] = EXIT_SUCCESS;
                    }
                }
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
     public function upload9()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
                log_message("error", 'inicio creacion reporte detalleplan parte 1'); 
                $this->crearCSVDetallePlan();
                log_message("error", 'termino crearCSVDetallePlan');
                $arrayInsertLogWU = array(
                    "descripcion" => 'termino de cargar upload9',
                    "fecha_registro" => $this->fechaActual(),
                );
    
                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                $data['error'] = EXIT_SUCCESS;
                
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function upload10()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
			$resultado = $this->crearCSVDetallePlan2();
			if($resultado['error']==EXIT_SUCCESS){
				$this->crearCSVDetallePlan3();
				log_message("error", 'termino crearCSVDetallePlan');
				$arrayInsertLogWU = array(
					"descripcion" => 'termino de cargar upload10',
					"fecha_registro" => $this->fechaActual(),
				);                    
				$data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
				$data['error'] = EXIT_SUCCESS;
			}                
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT1()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            log_message("error", 'entro al metodo uploadPOPEXT1');
            $this->generar_excelPE1();
            log_message("error", 'termino uploadPOPEXT1');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT1',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT1)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT2()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE2();
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT2',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            log_message("error", 'termino uploadPOPEXT2');
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT2)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT3()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE3();
            log_message("error", 'termino uploadPOPEXT3');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT3',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT3)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT4()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE4();
            log_message("error", 'termino uploadPOPEXT4');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT4',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT4)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT5()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE5();
            log_message("error", 'termino uploadPOPEXT5');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT5',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT5)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT6()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE6();
            log_message("error", 'termino uploadPOPEXT6');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT6',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT6)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT7()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE7();
            log_message("error", 'termino uploadPOPEXT7');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT7',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT7)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT8()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE8();
            log_message("error", 'termino uploadPOPEXT8');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT8',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT8)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT9()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE9();
            log_message("error", 'termino uploadPOPEXT9');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT9',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT9)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT10()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE10();
            log_message("error", 'termino uploadPOPEXT10');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT10',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT10)';
        }
        echo json_encode($data);
    }

    public function uploadPOPEXT11()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelPE11();
            log_message("error", 'termino uploadPOPEXT11');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOPEXT11',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPEXT11)';
        }
        echo json_encode($data);
    }

    public function uploadPOPINT()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $this->generar_excelP2();
            log_message("error", 'termino uploadPOINT');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar uploadPOINT',
                "fecha_registro" => $this->fechaActual(),
            );

            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (uploadPOPINT)';
        }
        echo json_encode($data);
    }

    public function crearExcelDetallePlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = '';
            $idEmpresaColab = '';
            $idPerfil = $this->session->userdata('idPerfilSession');
            if ($idPerfil == 6) {
                $idEmpresaColab = $this->session->userdata('eeccSession');
            }
            if ($this->session->userdata('zonasSession') != null) {
                $zonas = $this->session->userdata('zonasSession');
            }
            $detalleplan = $this->m_extractor->getDetallePlan($zonas, $idEmpresaColab);

            if (count($detalleplan) > 0) {
                log_message("error", 'inicio creacion detalle report');

                $this->load->library('excel');

                log_message("error", 'carga libreria excel');
                ini_set('max_execution_time', 10000);
                ini_set('memory_limit', '2048M');

                //ini_set('max_execution_time',20000);
                //ini_set('memory_limit', '3072M');
                //Cargamos la librerÃƒÂa de excel.
                $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
                PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle('DetalelPlan');
                $contador = 1;

                $titulosColumnas = array('ITEMPLAN', 'PO', 'AREA', 'PROYECTO', 'SUBPROYECTO', 'SISEGO TROBA', 'FECHA INICIO', 'FECHA PREVISTA', 'FECHA LIQUIDACION', 'FECHA CANCELACION', 'ESTADO', 'TITULO', 'JEFATURA', 'ZONAL', 'MDF', 'GRAFO', 'EMP. COLABORADORA', 'VALORIZ MANO DE OBRA', 'VALORIZ MATERIAL', 'VR', 'FECHA ULT. ESTADO', 'FECHA CREACION', 'USUARIO', 'ESTADO PLAN', 'CHECK', 'EXPEDIENTE', 'FECHA EXPEDIENTE', 'FECHA VALIDA EXP');

                $this->excel->setActiveSheetIndex(0);

                // Se agregan los titulos del reporte
                $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                    ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                    ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                    ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                    ->setCellValue('E1', utf8_encode($titulosColumnas[4]))
                    ->setCellValue('F1', utf8_encode($titulosColumnas[5]))
                    ->setCellValue('G1', utf8_encode($titulosColumnas[6]))
                    ->setCellValue('H1', utf8_encode($titulosColumnas[7]))
                    ->setCellValue('I1', utf8_encode($titulosColumnas[8]))
                    ->setCellValue('J1', utf8_encode($titulosColumnas[9]))
                    ->setCellValue('K1', utf8_encode($titulosColumnas[10]))
                    ->setCellValue('L1', utf8_encode($titulosColumnas[11]))
                    ->setCellValue('M1', utf8_encode($titulosColumnas[12]))
                    ->setCellValue('N1', utf8_encode($titulosColumnas[13]))
                    ->setCellValue('O1', utf8_encode($titulosColumnas[14]))
                    ->setCellValue('P1', utf8_encode($titulosColumnas[15]))
                    ->setCellValue('Q1', utf8_encode($titulosColumnas[16]))
                    ->setCellValue('R1', utf8_encode($titulosColumnas[17]))
                    ->setCellValue('S1', utf8_encode($titulosColumnas[18]))
                    ->setCellValue('T1', utf8_encode($titulosColumnas[19]))
                    ->setCellValue('U1', utf8_encode($titulosColumnas[20]))
                    ->setCellValue('V1', utf8_encode($titulosColumnas[21]))
                    ->setCellValue('W1', utf8_encode($titulosColumnas[22]))
                    ->setCellValue('X1', utf8_encode($titulosColumnas[23]))
                    ->setCellValue('Y1', utf8_encode($titulosColumnas[24]))
                    ->setCellValue('Z1', utf8_encode($titulosColumnas[25]))
                    ->setCellValue('AA1', utf8_encode($titulosColumnas[26]))
                    ->setCellValue('AB1', utf8_encode($titulosColumnas[27]));
                //Definimos la data del cuerpo.

                foreach ($detalleplan->result() as $row) {
                    $contador++;
                    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->itemPlan)
                        ->setCellValue("B{$contador}", $row->poCod)
                        ->setCellValue("C{$contador}", $row->areaDesc)
                        ->setCellValue("D{$contador}", $row->proyectoDesc)
                        ->setCellValue("E{$contador}", $row->subProyectoDesc)
                        ->setCellValue("F{$contador}", $row->indicador)
                        ->setCellValue("G{$contador}", $row->fechaInicio)
                        ->setCellValue("H{$contador}", $row->fechaPrevEjec)
                        ->setCellValue("I{$contador}", $row->fechaEjecucion)
                        ->setCellValue("J{$contador}", $row->fechaCancelacion)
                        ->setCellValue("K{$contador}", $row->est_innova)
                        ->setCellValue("L{$contador}", $row->titulo_trabajo)
                        ->setCellValue("M{$contador}", $row->jefatura)
                        ->setCellValue("N{$contador}", $row->zonal)
                        ->setCellValue("O{$contador}", $row->mdf)
                        ->setCellValue("P{$contador}", $row->grafo)
                        ->setCellValue("Q{$contador}", $row->eecc)
                        ->setCellValue("R{$contador}", $row->valoriz_m_o)
                        ->setCellValue("S{$contador}", $row->valoriz_material)
                        ->setCellValue("T{$contador}", $row->vr)
                        ->setCellValue("U{$contador}", $row->f_ult_est)
                        ->setCellValue("V{$contador}", $row->f_creac_prop)
                        ->setCellValue("W{$contador}", $row->usu_registro)
                        ->setCellValue("X{$contador}", $row->estadoPlanDesc)
                        ->setCellValue("Y{$contador}", $row->hasCheckPtr)
                        ->setCellValue("Z{$contador}", (($row->fecExpediente != null) ? '1' : '0'))
                        ->setCellValue("AA{$contador}", $row->fecExpediente)
                        ->setCellValue("AB{$contador}", $row->fecValExpediente);

                }

                $estiloTituloColumnas = array(
                    'font' => array(
                        'name' => 'Calibri',
                        'bold' => true,
                        'color' => array(
                            'rgb' => '000000',
                        ),
                    ));

                $this->excel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloColumnas);

                //Le ponemos un nombre al archivo que se va a generar.
                $archivo = "DetallePlan.xls";
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                //Hacemos una salida al navegador con el archivo Excel.
                $objWriter->save('download/detalleplan/' . $archivo);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }

   public function crearCSVDetallePlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = '';
            $idEmpresaColab = '';
            $idPerfil = $this->session->userdata('idPerfilSession');
            if ($idPerfil == 6) {
                $idEmpresaColab = $this->session->userdata('eeccSession');
            }
            if ($this->session->userdata('zonasSession') != null) {
                $zonas = $this->session->userdata('zonasSession');
            }
            $detalleplan = $this->m_extractor->getDetallePlan($zonas, $idEmpresaColab);

            if (count($detalleplan->result()) > 0) {
                $file = fopen(PATH_FILE_UPLOAD_DETALLE_PLAN, "w");
                fputcsv($file, explode('\t', "ITEMPLAN" . "\t" . "PO" . "\t" . "AREA" . "\t" . "PROYECTO" . "\t" . "SUBPROYECTO" . "\t" . "FASE". "\t" ."SISEGO TROBA" . "\t" . "FECHA CREACION IP" . "\t" . "FECHA INICIO" . "\t" . "FECHA PREVISTA" . "\t" . "FECHA TERMINO" . "\t" . "FECHA CANCELACION" . "\t" . "FECHA PRE LIQUIDACION" . "\t" . "ESTADO" . "\t" . "TITULO" . "\t" . "JEFATURA" . "\t" . "ZONAL" . "\t" . "MDF" . "\t" . "GRAFO" . "\t" . "EMP. COLABORADORA" . "\t" . "VALORIZ MANO DE OBRA" . "\t" . "VALORIZ MATERIAL" . "\t" . "VR" . "\t" . "FECHA ULT. ESTADO" . "\t" . "FECHA CREACION" . "\t" . "USUARIO" . "\t" . "ESTADO PLAN". "\t" . "DISTRITO". "\t" . "PEP 1". "\t" . "PEP 2"));
                foreach ($detalleplan->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" . $row->poCod . "\t" . $row->areaDesc . "\t" . $row->proyectoDesc . "\t" . $row->subProyectoDesc . "\t" .$row->faseDesc . "\t" .
                        $row->indicador . "\t" . $row->fecha_registro . "\t" . $row->fechaInicio . "\t" . $row->fechaPrevEjec . "\t" . $row->fechaEjecucion . "\t" . $row->fechaCancelacion . "\t" . $row->fechaPreLiquidacion . "\t" .
                        $row->est_innova . "\t" . $row->titulo_trabajo . "\t" . $row->jefatura . "\t" . $row->zonal . "\t" . $row->mdf . "\t" .
                        $row->grafo . "\t" . $row->eecc . "\t" . $row->valoriz_m_o . "\t" . $row->valoriz_material . "\t" . $row->vr . "\t" . $row->f_ult_est . "\t" . $row->f_creac_prop . "\t" . $row->usu_registro
                        . "\t" . $row->estadoPlanDesc. "\t" . $row->distrito. "\t" . $row->pep1. "\t" . $row->pep2)));

                    /*****
                $file = fopen(PATH_FILE_UPLOAD_DETALLE_PLAN, "w");
                fputcsv($file,  explode('\t',"ITEMPLAN"."\t"."PO"."\t"."AREA"."\t"."PROYECTO"."\t"."SUBPROYECTO"."\t"."SISEGO TROBA"."\t"."FECHA INICIO"."\t"."FECHA PREVISTA"."\t"."FECHA LIQUIDACION"."\t"."FECHA CANCELACION"."\t"."ESTADO"."\t"."TITULO"."\t"."JEFATURA"."\t"."ZONAL"."\t"."MDF"."\t"."GRAFO"."\t"."EMP. COLABORADORA"."\t"."VALORIZ MANO DE OBRA"."\t"."VALORIZ MATERIAL"."\t"."VR"."\t"."FECHA ULT. ESTADO"."\t"."FECHA CREACION"."\t"."USUARIO"."\t"."ESTADO PLAN"));
                foreach ($detalleplan->result() as $row){
                fputcsv($file, explode('\t', utf8_decode($row->itemPlan."\t". $row->poCod."\t". $row->areaDesc."\t". $row->proyectoDesc."\t".$row->subProyectoDesc."\t".
                $row->indicador."\t". $row->fechaInicio."\t". $row->fechaPrevEjec."\t". $row->fechaEjecucion."\t".$row->fechaCancelacion."\t".
                $row->est_innova."\t". $row->titulo_trabajo."\t". $row->jefatura."\t". $row->zonal."\t".$row->mdf."\t".
                $row->grafo."\t". $row->eecc."\t". $row->valoriz_m_o."\t". $row->valoriz_material."\t". $row->vr."\t". $row->f_ult_est."\t". $row->f_creac_prop."\t". $row->usu_registro
                ."\t". $row->estadoPlanDesc)));****/
                }
                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }
	/*
    public function crearCSVDetallePlan2()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = '';
            $idEmpresaColab = '';
            $idPerfil = $this->session->userdata('idPerfilSession');
            if ($idPerfil == 6) {
                $idEmpresaColab = $this->session->userdata('eeccSession');
            }
            if ($this->session->userdata('zonasSession') != null) {
                $zonas = $this->session->userdata('zonasSession');
            }
            $detalleplan = $this->m_extractor->getDetallePlan2($zonas, $idEmpresaColab);

            if (count($detalleplan->result()) > 0) {
                $file = fopen(PATH_FILE_UPLOAD_DETALLE_PLAN, "a");
                foreach ($detalleplan->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" . $row->poCod . "\t" . $row->areaDesc . "\t" . $row->proyectoDesc . "\t" . $row->subProyectoDesc . "\t" .$row->faseDesc . "\t" .
                        $row->indicador . "\t" . $row->fecha_registro . "\t" . $row->fechaInicio . "\t" . $row->fechaPrevEjec . "\t" . $row->fechaEjecucion . "\t" . $row->fechaCancelacion . "\t" . $row->fechaPreLiquidacion . "\t" .
                        $row->est_innova . "\t" . $row->titulo_trabajo . "\t" . $row->jefatura . "\t" . $row->zonal . "\t" . $row->mdf . "\t" .
                        $row->grafo . "\t" . $row->eecc . "\t" . $row->valoriz_m_o . "\t" . $row->valoriz_material . "\t" . $row->vr . "\t" . $row->f_ult_est . "\t" . $row->f_creac_prop . "\t" . $row->usu_registro
                        . "\t" . $row->estadoPlanDesc. "\t".$row->distrito)));
                }
                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }
*/
	public function crearCSVDetallePlan2()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $detalleplan = $this->m_extractor->getDetallePlan2();
            if (count($detalleplan->result()) > 0) {
                $file = fopen(PATH_FILE_UPLOAD_DETALLE_PLAN, "a");
                foreach ($detalleplan->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" . $row->poCod . "\t" . $row->areaDesc . "\t" . $row->proyectoDesc . "\t" . $row->subProyectoDesc . "\t" .$row->faseDesc . "\t" .
                        $row->indicador . "\t" . $row->fecha_registro . "\t" . $row->fechaInicio . "\t" . $row->fechaPrevEjec . "\t" . $row->fechaEjecucion . "\t" . $row->fechaCancelacion . "\t" . $row->fechaPreLiquidacion . "\t" .
                        $row->est_innova . "\t" . $row->titulo_trabajo . "\t" . $row->jefatura . "\t" . $row->zonal . "\t" . $row->mdf . "\t" .
                        $row->grafo . "\t" . $row->eecc . "\t" . $row->valoriz_m_o . "\t" . $row->valoriz_material . "\t" . $row->vr . "\t" . $row->f_ult_est . "\t" . $row->f_creac_prop . "\t" . $row->usu_registro
                        . "\t" . $row->estadoPlanDesc. "\t".$row->distrito. "\t" . $row->pep1. "\t" . $row->pep2)));
                }
                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }
	
	 public function crearCSVDetallePlan3()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
              
            $detalleplan = $this->m_extractor->getDetallePlan3();    
            if (count($detalleplan->result()) > 0) {
                $file = fopen(PATH_FILE_UPLOAD_DETALLE_PLAN, "a");
                foreach ($detalleplan->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" . $row->poCod . "\t" . $row->areaDesc . "\t" . $row->proyectoDesc . "\t" . $row->subProyectoDesc . "\t" .$row->faseDesc . "\t" .
                        $row->indicador . "\t" . $row->fecha_registro . "\t" . $row->fechaInicio . "\t" . $row->fechaPrevEjec . "\t" . $row->fechaEjecucion . "\t" . $row->fechaCancelacion . "\t" . $row->fechaPreLiquidacion . "\t" .
						$row->est_innova . "\t" . $row->titulo_trabajo . "\t" . $row->jefatura . "\t" . $row->zonal . "\t" . $row->mdf . "\t" .
						$row->grafo . "\t" . $row->eecc . "\t" . $row->valoriz_m_o . "\t" . $row->valoriz_material . "\t" . $row->vr . "\t" . $row->f_ult_est . "\t" . $row->f_creac_prop . "\t" . $row->usu_registro
						. "\t" . $row->estadoPlanDesc. "\t".$row->distrito. "\t" .$row->pep1. "\t" .$row->pep2)));
                }
                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }

    public function generar_excelP()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPlanObra($user, $var);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "w");
                fputcsv($file, explode('\t', "ITEMPLAN" . "\t" .
                    "PROYECTO" . "\t" .
                    "SUBPROYECTO" . "\t" .
                    "INDICADOR" . "\t" .
                    "NOMBRE PROYECTO" . "\t" .
                    "FASE" . "\t" .
                    "EMPRESA ELECTRICA" . "\t" .
                    "UIP" . "\t" .
                    "COORDX" . "\t" .
                    "COORDY" . "\t" .
                    "ESTADO PLAN" . "\t" .
                    "FECHA CREACION IP" . "\t" .
                    "FECHA INICIO" . "\t" .
                    "FECHA PREVISTA EJECUCION" . "\t" .
                    "FECHA ADJUDICACION DISENO" . "\t" .
                    "FECHA EJECUCION DISENO" . "\t" .
                    "FECHA TERMINO" . "\t" .
                    "FECHA DE PRE LIQUIDACION" . "\t" .
                    "CENTRAL" . "\t" .
                    "JEFATURA" . "\t" .
                    "REGION" . "\t" .
                    "ZONAL" . "\t" .
                    "EMPRESA COLABORADORA" . "\t" .
                    "EMPRESA ADJUDICACION" . "\t" .
                    "ADELANTO" . "\t" .
                    "MARCA PARALIZACION" . "\t" .
                    "FECHA PARALIZACION" . "\t" .
                    "MOTIVO PARALIZACION" . "\t" .
                    "FECHA DESPARALIZACION" . "\t" .
                    "SIROPE" . "\t" .
                    "CODIGO DE TRABAJO"
                ));

                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE1()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 1);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "w");
                fputcsv($file, explode('\t', "ITEMPLAN" . "\t" .
                    "PROYECTO" . "\t" .
                    "SUBPROYECTO" . "\t" .
                    "INDICADOR" . "\t" .
                    "NOMBRE PROYECTO" . "\t" .
                    "FASE" . "\t" .
                    "EMPRESA ELECTRICA" . "\t" .
                    "UIP" . "\t" .
                    "COORDX" . "\t" .
                    "COORDY" . "\t" .
                    "ESTADO PLAN" . "\t" .
                    "FECHA CREACION IP" . "\t" .
                    "FECHA INICIO" . "\t" .
                    "FECHA PREVISTA EJECUCION" . "\t" .
                    "FECHA ADJUDICACION DISENO" . "\t" .
                    "FECHA EJECUCION DISENO" . "\t" .
                    "FECHA TERMINO" . "\t" .
                    "FECHA DE PRE LIQUIDACION" . "\t" .
                    "CENTRAL" . "\t" .
                    "JEFATURA" . "\t" .
                    "REGION" . "\t" .
                    "ZONAL" . "\t" .
                    "EMPRESA COLABORADORA" . "\t" .
                    "EMPRESA ADJUDICACION" . "\t" .
                    "ADELANTO" . "\t" .
                    "MARCA PARALIZACION" . "\t" .
                    "FECHA PARALIZACION" . "\t" .
                    "MOTIVO PARALIZACION" . "\t" .
                    "FECHA DESPARALIZACION" . "\t" .
                    "SIROPE" . "\t" .
                    "CODIGO DE TRABAJO"
                ));

                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE2()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 2);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE3()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 3);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE4()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 4);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
            
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE5()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 5);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE6()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 6);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE7()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 7);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE8()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 8);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE9()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 9);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE10()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 10);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                

                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelPE11()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOEXT($user, $var, 11);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                

                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

    public function generar_excelP2()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');

            $var = ($zonas != null ? $zonas : '');

            $planobra = $this->m_extractor->getPOPINT($user, $var);

            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "a");
                
                foreach ($planobra->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->empresaElecDesc . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_registro . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fecha_adjudicacion . "\t" .
                        $row->fecha_ejecucion_diseno . "\t" .
                        $row->fechaEjecucion . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->empresaColabDiseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" . $row->fechaParaLizacion . "\t" .
                        $row->TipoMotivo . "\t" .
                        $row->fechaDesParaLizacion . "\t" .
                        $row->estado_actual . "\t" .
                        $row->codigo)));

                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;

    }

/***************************************************MIGUEL RIOS CREAR ARCHIVO VALE DE RESERVA 14052018******************************************/

    public function crearCSVItemValeReserva()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $idPerfil = $this->session->userdata('idPerfilSession');

            $resultado = $this->m_extractor->getItemplanValeReserva();

            if ($resultado->num_rows() > 0) {

                $file = fopen(PATH_FILE_UPLOAD_VALE_RESERVA, "w");
                /****************************************miguel rios 02072018
                fputcsv($file,  explode('\t',"ITEMPLAN"."\t"."ESTADO"."\t"."PTR"."\t"."ESTADO PTR"."\t"."FECHA APROB"."\t"."PORCENTAJE"."\t"."AREA"."\t"."ESTACION"."\t"."VALE RESERVA"."\t"."VALORIZ_MATERIAL"."\t"."VALORIZ_MO"));
                foreach ($resultado->result() as $row){
                fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->estado."\t". $row->ptr."\t". $row->estadoptr."\t".$row->fechaaprob."\t".
                $row->porcentaje."\t". $row->desc_area."\t". $row->estacion."\t". $row->valereserva."\t".$row->valor_material."\t".
                $row->valor_mo)));
                }***************************************************/
                fputcsv($file, explode('\t', "ITEMPLAN" . "\t" . "ESTADO" . "\t" . "PTR" . "\t" . "ESTADO PTR" . "\t" . "FECHA APROB" . "\t" . "AREA" . "\t" . "VALE RESERVA" . "\t" . "VALORIZ_MATERIAL" . "\t" . "VALORIZ_MO"));

                foreach ($resultado->result() as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" . $row->estado . "\t" . $row->ptr . "\t" . $row->estadoptr . "\t" . $row->fechaaprob . "\t" . $row->desc_area . "\t" . $row->valereserva . "\t" . $row->valor_material . "\t" . $row->valor_mo)));
                }

                fclose($file);
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo de vale de reserva';
        }
        return $data;
    }

    public function crearCVSCertificacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $toro = $this->m_extractor->ListarExtractor();

            $file = fopen(PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION, "w");
            fputcsv($file, explode('\t', "ItemPlan" . "\t" .
                "PTR" . "\t" .
                "AREA" . "\t" .
                "indicador" . "\t" .
                "Estacion" . "\t" .
                "Porcentaje" . "\t" .
                "Proyecto" . "\t" .
                "SubProyecto" . "\t" .
                "Fecha Creacion IP" . "\t" .
                "Prevista" . "\t" .
                "Fecha Ejecucion" . "\t" .
                "Estado PTR" . "\t" .
                "Estado IP" . "\t" .
                "Colab IP" . "\t" .
                "Colab PTR" . "\t" .
                "Zonal" . "\t" .
                "Jefatura" . "\t" .
                "HG" . "\t" .
                "Orden Compra" . "\t" .
                "Certificacion" . "\t" .
                "Monto Mat" . "\t" .
                "Monto Mo" . "\t" .
                "Ultimo Estado" . "\t" .
                "Usuario Ultimo Estado" . "\t" .
                "Certificable" . "\t" .
                "Situacion" . "\t" .
                "Expediente" . "\t" .
                "Fecha Expediente" . "\t" .
                "Validado" . "\t" .
                "Fecha Valida" . "\t" .
                "Usuario Valida". "\t" .
                "Distrito". "\t" .
                "Estado Sirope"));

            foreach ($toro->result() as $row) {

                fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" .
                    $row->poCod . "\t" .
                    $row->tipoArea . "\t" .
                    $row->indicador . "\t" .
                    $row->estacionDesc . "\t" .
                    $row->porcentaje . "\t" .
                    $row->proyectoDesc . "\t" .
                    $row->subProyectoDesc . "\t" .
                    $row->fecha_creacion . "\t" .
                    $row->fechaPrevEjec . "\t" .
                    $row->fechaEjecucion . "\t" .
                    $row->est_innova . "\t" .
                    $row->estadoPlanDesc . "\t" .
                    $row->empresaColabDesc . "\t" .
                    $row->eecc . "\t" .
                    $row->zonalDesc . "\t" .
                    $row->jefatura . "\t" .
                    $row->h_gestion . "\t" .
                    $row->oc . "\t" .
                    $row->ncert . "\t" .
                    $row->valoriz_material . "\t" .
                    $row->valoriz_m_o . "\t" .
                    $row->f_ult_est . "\t" .
                    $row->usu_registro . "\t" .
                    $row->certificable . "\t" .
                    $row->estado_vali . "\t" .
                    $row->expediente . "\t" .
                    $row->fecha . "\t" .
                    $row->validado . "\t" .
                    $row->fecha_valida . "\t" .
                    $row->usuario_valida. "\t" .
                    $row->distrito. "\t" .
                    $row->ult_estado_sirope)));

            }

            fclose($file);
            //$data['error'] = EXIT_SUCCESS;
            $toro2 = $this->m_extractor->ListarExtractor2_0();            
            $file = fopen(PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION, "a");
            foreach ($toro2->result() as $row) {
            
                fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" .
                $row->poCod . "\t" .
                $row->tipoArea . "\t" .
                $row->indicador . "\t" .
                $row->estacionDesc . "\t" .
                $row->porcentaje . "\t" .
                $row->proyectoDesc . "\t" .
                $row->subProyectoDesc . "\t" .
                $row->fecha_creacion . "\t" .
                $row->fechaPrevEjec . "\t" .
                $row->fechaEjecucion . "\t" .
                $row->est_innova . "\t" .
                $row->estadoPlanDesc . "\t" .
                $row->empresaColabDesc . "\t" .
                $row->eecc . "\t" .
                $row->zonalDesc . "\t" .
                $row->jefatura . "\t" .
                $row->h_gestion . "\t" .
                $row->oc . "\t" .
                $row->ncert . "\t" .
                $row->valoriz_material . "\t" .
                $row->valoriz_m_o . "\t" .
                $row->f_ult_est . "\t" .
                $row->usu_registro . "\t" .
                $row->certificable . "\t" .
                $row->estado_vali . "\t" .
                $row->expediente . "\t" .
                $row->fecha . "\t" .
                $row->validado . "\t" .
                $row->fecha_valida . "\t" .
                $row->usuario_valida. "\t" .
                $row->distrito. "\t" .
                $row->ult_estado_sirope)));        
            }            
            fclose($file);   
            $data = $this->m_extractor->getImportCertificacionTabla(PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception("ERROR CARGA TABLA import_report_certificacion");
            } else {
                $data = $this->m_extractor->exeCorreccionDataCert();
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception("ERROR CARGA TABLA import_report_certificacion");
                } else {
                    $data = $this->crearCVSCertificacion2(1);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $data = $this->crearCVSCertificacion2(2);
                        if ($data['error'] == EXIT_SUCCESS) {
                            log_message('error', 'setArchivoCVSInToTablaTemp:');
                            $data = $this->setArchivoCVSInToTablaTemp();
                        }
                    } else {
                        log_message('error', 'pre_certi_2_error:' . $data['error']);
                    }
                }

            }

        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo de certificacion';
        }
        return $data;
    }

    public function crearCVSCertificacion2($accion)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            if ($accion == 1) {
                $toro = $this->m_extractor->ListarExtractor2();
            } else if ($accion == 2) {
                $toro = $this->m_extractor->ListarExtractor3();
            }

            $file = fopen(PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION, "a");

            foreach ($toro->result() as $row) {

                fputcsv($file, explode('\t', utf8_decode($row->itemplan . "\t" .
                    $row->poCod . "\t" .
                    $row->tipoArea . "\t" .
                    $row->indicador . "\t" .
                    $row->estacionDesc . "\t" .
                    $row->porcentaje . "\t" .
                    $row->proyectoDesc . "\t" .
                    $row->subProyectoDesc . "\t" .
                    $row->fecha_creacion . "\t" .
                    $row->fechaPrevEjec . "\t" .
                    $row->fechaEjecucion . "\t" .
                    $row->est_innova . "\t" .
                    $row->estadoPlanDesc . "\t" .
                    $row->empresaColabDesc . "\t" .
                    $row->eecc . "\t" .
                    $row->zonalDesc . "\t" .
                    $row->jefatura . "\t" .
                    $row->h_gestion . "\t" .
                    $row->oc . "\t" .
                    $row->ncert . "\t" .
                    $row->valoriz_material . "\t" .
                    $row->valoriz_m_o . "\t" .
                    $row->f_ult_est . "\t" .
                    $row->usu_registro . "\t" .
                    $row->certificable . "\t" .
                    $row->estado_vali . "\t" .
                    $row->expediente . "\t" .
                    $row->fecha . "\t" .
                    $row->validado . "\t" .
                    $row->fecha_valida . "\t" .
                    $row->usuario_valida. "\t" .
                    $row->distrito. "\t" .
                    $row->ult_estado_sirope)));

            }

            fclose($file);
            $data['error'] = EXIT_SUCCESS;
            log_message('error', 'set success');
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo de certificacion';
        }
        log_message('error', 'vuelta_mo:' . $data['error']);
        return $data;
    }

    public function validaFechaCertifica($fecha1)
    {
        $fecha2 = '2018-07-06';
        return (strtotime($fecha1) > strtotime($fecha2));
    }

    public function setArchivoCVSInToTablaTemp(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }

            $data = $this->m_extractor->setReporteIntoTableTemp('download/liquidacion/certificacionCSV.csv');
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception("ERROR CARGA import_cert_cv_temp");
            }else{
                $data = $this->m_extractor->corregirDataTablaImportCertTempCV();
                if($data['error'] == EXIT_SUCCESS){
                    $data = $this->m_extractor->fcInsertTablaImportCertCV();
                }
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
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

 public function uploadPO2_0()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            log_message("error", 'inicio creacion reporte crearCSVPlanObra2_0');
            $this->crearCSVPlanObra2_0();
            log_message("error", 'termino crearCSVPlanObra2_0');
            $arrayInsertLogWU = array(
                "descripcion" => 'termino de cargar PLANOBRA',
                "fecha_registro" => $this->fechaActual(),
            );
    
            $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            /**nuevo para el reporte de activaciones**/
            $data = $this->m_tranferencia_wu->execLoadActivaciones();
            if ($data['error'] == EXIT_SUCCESS) {                
                $arrayInsertLogWU = array(
                    "descripcion" => 'Ejecuto Funcion Activaciones',
                    "fecha_registro" => $this->fechaActual(),
                );                
                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                 if ($data['error'] == EXIT_SUCCESS) {    
                     $data = $this->generarReporteActivaciones();
                     if ($data['error'] == EXIT_SUCCESS) {
                         $arrayInsertLogWU = array(
                             "descripcion" => 'Se Genero Reporte Activaciones',
                             "fecha_registro" => $this->fechaActual(),
                         );
                         $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
                     }
                 }
            }
            /******************************************/
            $data['error'] = EXIT_SUCCESS;
    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function crearCSVPlanObra2_0()
    {
    
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $user = $this->session->userdata('idPersonaSession');
            $zonas = $this->session->userdata('zonasSession');
    
            $var = ($zonas != null ? $zonas : '');
    
            $planobra = $this->m_extractor->getPlanObra_2_0();
            if (count($planobra->result()) > 0) {
                $file = fopen("download/planobra/planobraCSV.csv", "w");
                fputcsv($file, explode('\t', "ITEMPLAN" . "\t" .
                    "PROYECTO" . "\t" .
                    "SUBPROYECTO" . "\t" .
                    "INDICADOR" . "\t" .
                    "NOMBRE PROYECTO" . "\t" .
                    "FASE" . "\t" .
                    "EMPRESA ELECTRICA" . "\t" .
                    "UIP" . "\t" .
                    "COORDX" . "\t" .
                    "COORDY" . "\t" .
                    "ESTADO PLAN" . "\t" .
                    "FECHA CREACION IP" . "\t" .
                    "FECHA INICIO" . "\t" .
                    "FECHA PREVISTA EJECUCION" . "\t" .
                    "FECHA ADJUDICACION DISENO" . "\t" .
                    "FECHA EJECUCION DISENO" . "\t" .
                    "FECHA TERMINO" . "\t" .
                    "FECHA DE PRE LIQUIDACION" . "\t" .
                    "CENTRAL" . "\t" .
                    "JEFATURA" . "\t" .
                    "REGION" . "\t" .
                    "ZONAL" . "\t" .
                    "EMPRESA COLABORADORA" . "\t" .
                    "EMPRESA ADJUDICACION" . "\t" .
                    "ADELANTO" . "\t" .
                    "MARCA PARALIZACION" . "\t" .
                    "FECHA PARALIZACION" . "\t" .
                    "MOTIVO PARALIZACION" . "\t" .
                    "FECHA DESPARALIZACION" . "\t" .
                    "SIROPE" . "\t" .
                    "CODIGO DE TRABAJO"."\t".
                    "CODIGO MDF". "\t" .
                    "PAQUETIZADO". "\t" .
                    "DEPARTAMENTO"."\t".
                    "PROVINCIA"."\t".
                    "DISTRITO"."\t".
                    "CESTA"."\t".
                    "ORDEN COMPRA"."\t".
					"NRO CERTIFICA CV"."\t".
                    "POSICION"."\t".
                    "SOLICITUD OC"."\t".
                    "ESTADO SOLICITUD OC"."\t".
					"FECHA REGISTRO SOLICITUD OC"."\t".
                    "FECHA TRUNCO"."\t".
                    "FECHA CANCELACION"."\t".
                    "ESTADO - PRESUP"));
    
                foreach ($planobra->result() as $row) {
                    $ubicacion = count(explode('|', $row->ubicacion)) == 3 ? explode('|', $row->ubicacion) : array('', '', ''); 
                    fputcsv($file, explode('\t', utf8_decode(trim($row->itemPlan). "\t" .
                        trim($row->proyectoDesc) . "\t" .
                        trim($row->subProyectoDesc) . "\t" .
                        trim($row->indicador). "\t" .
                        trim($row->nombreProyecto). "\t" .
                        trim($row->faseDesc). "\t" .
                        trim($row->ec_elec). "\t" .
                        trim($row->uip). "\t" .
                        trim($row->coordX). "\t" .
                        trim($row->coordY). "\t" .
                        trim($row->estadoPlanDesc). "\t" .
                        trim($row->fecha_creacion). "\t" .
                        trim($row->fechaInicio). "\t" .
                        trim($row->fechaPrevEjec). "\t" .
                        trim($row->fec_ult_adju_diseno). "\t" .
                        trim($row->fec_ult_ejec_diseno). "\t" .
                        trim($row->fechaTermino). "\t" .
                        trim($row->fechaPreLiquidacion). "\t" .
                        trim($row->tipoCentralDesc). "\t" .
                        trim($row->jefatura). "\t" .
                        trim($row->region). "\t" .
                        trim($row->zonalDesc)."\t" .
                        trim($row->empresaColabDesc). "\t" .
                        trim($row->ec_diseno). "\t" . 
                        trim($row->hasAdelanto). "\t" .
                        trim($row->flagParalizacion). "\t" . 
                        trim($row->fechaRegistro). "\t" .
                        trim($row->motivoDesc). "\t" .
                        trim($row->fechaReactivacion). "\t" .
                        trim($row->ult_estado_sirope). "\t" .
                        trim($row->ult_codigo_sirope). "\t" .
                        trim($row->codigo). "\t" .
                        trim($row->paquetizado). "\t" .
                        trim($ubicacion[0]). "\t" .
                        trim($ubicacion[1]). "\t" .
                        trim($ubicacion[2]). "\t" .
                        trim($row->cesta). "\t" .
                        trim($row->orden_compra). "\t" .
						trim($row->nro_certificacion_cv). "\t" .
                        trim($row->posicion). "\t" .
                        trim($row->solicitud_oc). "\t" .
                        trim($row->estado_sol_oc). "\t" .
						trim($row->fec_registro_sol_creacion_oc). "\t" .
                        trim($row->fechaTrunca). "\t" .
                        trim($row->fechaCancelacion). "\t" .
                        trim($row->has_ppto))));
                }
    
                fclose($file);
                $data['error'] = EXIT_SUCCESS;
                $this->insertReporte();
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo planobra';
        }
        return $data;
    }
    
     /***Generar reporte off line  ACTIVACIONES 27.06.2019**/
    function generarReporteActivaciones() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
           
            $detalleplan = $this->m_extractor->getDataReporteActivaciones();
            if(count($detalleplan->result()) > 0) {
    
                $file = fopen(PATH_FILE_REPORTE_ACTIVACIONES, "w");
                fputcsv($file, explode('\t',"PEP1"."\t".
                    "PEP2"."\t".
                    "GRAFO"."\t".
                    "PLAN"."\t".
                    "PRESUPUESTO"."\t".
                    "REAL"."\t".
                    "COMPROMETIDO"."\t".
                    "PLANRESORD"."\t".
                    "ASIGNADO"."\t".
                    "NULO"."\t".
                    "DISPONIBLE"."\t".
                    "TIPO"."\t".
                    "PTR"."\t".
                    "COSTO TOTAL"."\t".
                    "ESTADO"."\t".
                    "ITEMPLAN"."\t".
                    "SUBPROYECTO"."\t".
                    "PROYECTO"."\t".
                    "ESTADO PLAN"."\t".
                    "ESTACION"."\t".
                    "EXPEDIENTE"."\t".
                    "VALIDADO"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($file, explode('\t', utf8_decode($row->pep1."\t".
                            $row->pep2."\t".
                            $row->grafo."\t".
                            $row->plan."\t".
                            $row->presupuesto."\t".
                            $row->real."\t".
                            $row->comprometido."\t".
                            $row->planresord."\t".
                            $row->asignado."\t".
                            $row->nulo."\t".
                            $row->disponible."\t".
                            $row->tipo_ptr."\t".
                            $row->ptr."\t".
                            $row->costo_total."\t".
                            $row->estado."\t".
                            $row->itemplan."\t".
                            $row->subProyectoDesc."\t".
                            $row->proyectoDesc."\t".
                            $row->estadoPlanDesc."\t".
                            $row->estacionDesc."\t".
                            $row->has_expe."\t".
                            $row->expe_validado)));
                }
    
                fclose($file);
            } else {
                throw new Exception('no hay data en la carga');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function insertReporte() {
        $this->m_tranferencia_wu->insertReporte($this->fechaActual());
    }
	
	 /***Generar reporte DIAGNOSTRICO PEPS GENERAL OFFLINE  23.03.2020**/
    function generarReporteDiagnosticoPep() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
           
            $detalleplan = $this->m_extractor->getDataReporteDiagnosticoPep();
            if(count($detalleplan->result()) > 0) {
    
                $file = fopen(PATH_FILE_REPORTE_DIAGNOSTICO_PEP, "w");
                fputcsv($file, explode('\t',"SUBPROYECTO"."\t".
                    "ITEMPLAN"."\t".
                    "INDIDCADOR"."\t".
                    "EECC"."\t".
                    "ESTADO PLAN"."\t".
                    "FASE"."\t".
					"PARALIZADO"."\t".
					"MOTIVO PARALIZADO"."\t".
                    "COSTO UNITARIO MO"."\t".
                    "COSTO UNITARIO MAT"."\t".
                    "COSTO TOTAL"."\t".
					"PEP1"."\t".
                    "PEP2"."\t".
					"DISPONIBLE PEP"."\t".
                    "GRAFO"."\t".
                    "SOLICITUD OC"."\t".
                    "SITUACION"."\t".
                    "ORDEN COMPRA"."\t".
                    "VALE RESERVA"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($file, explode('\t', utf8_decode($row->subProyectoDesc."\t".
                            $row->itemplan."\t".
                            $row->indicador."\t".
                            $row->empresaColabDesc."\t".
                            $row->estadoPlanDesc."\t".
                            $row->faseDesc."\t".
							$row->paralizado."\t".
							$row->motivoDesc."\t".
                            $row->costo_unitario_mo_format."\t".
                            $row->costo_unitario_mat_format."\t".
                            $row->total_format."\t".
							$row->pep1."\t".
                            $row->pep2."\t".
							$row->monto_temporal."\t".
                            $row->grafo."\t".
                            $row->solicitud_oc."\t".
                            $row->situacion."\t".
                            $row->con_oc."\t".
                            $row->con_vr)));
                }
    
                fclose($file);
            } else {
                throw new Exception('no hay data en la carga');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
