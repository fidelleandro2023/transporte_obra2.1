<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_registro_masivo_lic extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc = $this->session->userdata('eeccSession');
        if ($logedUser != null) {

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_REG_MASIVO_LIC);

            $data['title'] = 'REGISTRO MASIVO LICENCIAS';

            $data['opciones'] = $result['html'];
            $this->load->view('vf_detalle_obra/v_registro_masivo_lic', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function cargarArchivoLic()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $path = $_FILES["file"]["tmp_name"];

            $object = PHPExcel_IOFactory::load($path);
            log_message('error', 'entro al metodo');

            $file2 = utf8_decode($file);
            $ubicacion = 'uploads/registro_masivo_lic';

            $this->db->trans_begin();

            if (!is_dir($ubicacion)) {
                mkdir('uploads/registro_masivo_lic', 0777);
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $ubicacion . "/" . $file2)) {
                //log_message('error', 'subio el archivo');
            } else {
                throw new Exception('ND');
            }

            $arrayLic = array();
            $arrayReembolso = array();
            $arrayIPNoExist = array();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    //LINCENCIAS
                    $idEntidad = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $idEstacion = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $itemplan = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $fechaInicio = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $fechaFin = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $idUsuarioReg = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $idDistrito = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $cod_expediente = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $flgTipoLic = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    //REEMBOLSO
                    $desc_reembolso = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $fecha_emision = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $monto = $worksheet->getCellByColumnAndRow(12, $row)->getValue();

                    if ($itemplan != null) {
                        $existeIP = $this->m_utils->countItemplan($itemplan);
                        // $flgExisteLic = $this->m_utils->countIPEstConcluido($itemplan,$idEstacion,$idEntidad);
                    }

                    if ($existeIP > 0) {
                        if ($idEntidad != null && $idEstacion != null && $itemplan != null && $fechaInicio != null && $fechaFin != null && $idUsuarioReg != null && $idDistrito != null && $cod_expediente != null && $flgTipoLic != null) {
                        // if($idEntidad != null && $idEstacion != null && $itemplan != null && $fechaFin != null && $idUsuarioReg != null && $idDistrito != null && $cod_expediente != null && $flgTipoLic != null){    
                            // if($flgExisteLic > 0){
                                $arrayLic = $this->pushArrayExcelLic($idEntidad, $idEstacion, $itemplan, $fechaInicio, $fechaFin, $idUsuarioReg, $idDistrito, $cod_expediente, $flgTipoLic, $arrayLic);
                                // $arrayLic = $this->pushArrayExcelFinalizacion($idEntidad, $idEstacion, $itemplan, $fechaFin, $idUsuarioReg, $idDistrito, $cod_expediente, $flgTipoLic, $arrayLic);
                            // }
                        // }
                            
                        }
                        if ($desc_reembolso != null && $fecha_emision != null && $monto != null) {
                            $arrayReembolso = $this->pushArrayExcelReem($desc_reembolso, $fecha_emision, $monto, $arrayReembolso);
                        }
                    } else {
                        $arrayIPNoExist[] = $itemplan;
                    }
                }
            }

            $count = 0;
            $ids = array();

            foreach ($arrayLic as $row) {
                $arrayInsertTemp = array(
                    "idEntidad" => $row['idEntidad'],
                    "idEstacion" => $row['idEstacion'],
                    "itemPlan" => $row['itemPlan'],
                    "fecha_inicio" => $row['fecha_inicio'],
                    "fecha_fin" => $row['fecha_fin'],
                    "id_usuario_reg" => $row['id_usuario_reg'],
                    "fecha_registro" => $row['fecha_registro'],
                    "flg_validado" => $row['flg_validado'],
                    "idDistrito" => $row['idDistrito'],
                    "codigo_expediente" => $row['codigo_expediente'],
                    "flg_tipo" => $row['flg_tipo'],
                );

                // $arrayInsertTemp = array(
                //     "idEntidad" => $row['idEntidad'],
                //     "idEstacion" => $row['idEstacion'],
                //     "itemPlan" => $row['itemPlan'],
                //     "fecha_final" => $row['fecha_final'],
                //     "id_usuario_reg" => $row['id_usuario_reg'],
                //     "id_usuario_liquida" => $row['id_usuario_reg'],
                //     "fecha_liquidacion" => $row['fecha_liquidacion'],
                //     "flg_validado" => $row['flg_validado'],
                //     "idDistrito" => $row['idDistrito'],
                //     "cod_expe_finalizacion" => $row['codigo_expe_finalizacion'],
                //     "flg_tipo" => $row['flg_tipo'],
                // );

                $data = $this->m_utils->insertarIPEstLicDet($arrayInsertTemp);    
                $ids[] = $data['iditemplan_estacion_licencia_det'];
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayReembolso[$count]['iditemplan_estacion_licencia_det'] = $data['iditemplan_estacion_licencia_det'];
                }

                $count++;
            }

            // log_message('error',print_r($arrayLic,true));
            // log_message('error',count($ids));
            // log_message('error',print_r($arrayReembolso,true));

            if (count($ids) == 0) {
                throw new Exception('Hubo un error al insertar las licencias');
            }

            $data = $this->m_utils->insertBatchReembolso($arrayReembolso);

            // $this->db->trans_rollback();

            if ($data['error'] == EXIT_SUCCESS) {
                $this->db->trans_commit();
            }else{
                $this->db->trans_rollback();
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function pushArrayExcelLic($idEntidad, $idEstacion, $itemplan, $fechaInicio, $fechaFin, $idUsuarioReg, $idDistrito, $cod_expediente, $flgTipoLic, $arrayLic)
    {

        $json['idEntidad'] = $idEntidad;
        $json['idEstacion'] = $idEstacion;
        $json['itemPlan'] = trim($itemplan, "'");
        $json['fecha_inicio'] = trim($fechaInicio, "'");
        $json['fecha_fin'] = trim($fechaFin, "'");
        $json['id_usuario_reg'] = $idUsuarioReg;
        $json['fecha_registro'] = $this->fechaActual();
        $json['flg_validado'] = '1';
        $json['idDistrito'] = $idDistrito;
        $json['codigo_expediente'] = trim($cod_expediente, "'");
        $json['flg_tipo'] = $flgTipoLic;

        array_push($arrayLic, $json);
        return $arrayLic;
    }

    public function pushArrayExcelReem($desc_reembolso, $fecha_emision, $monto, $arrayReembolso)
    {
        $json['desc_reembolso'] = trim($desc_reembolso, "'");
        $json['fecha_emision'] = trim($fecha_emision, "'");
        $json['monto'] = $monto;
        $json['fecha_registro'] = $this->fechaActual();
        $json['estado_valida'] = '1';

        array_push($arrayReembolso, $json);
        return $arrayReembolso;
    }

    public function pushArrayExcelFinalizacion($idEntidad, $idEstacion, $itemplan, $fechaFin, $idUsuarioReg, $idDistrito, $cod_expediente, $flgTipoLic, $arrayLic)
    {

        $json['idEntidad'] = $idEntidad;
        $json['idEstacion'] = $idEstacion;
        $json['itemPlan'] = trim($itemplan, "'");
        $json['fecha_final'] = trim($fechaFin, "'");
        $json['id_usuario_reg'] = $idUsuarioReg;
        $json['fecha_liquidacion'] = $this->fechaActual();
        $json['flg_validado'] = '3';
        $json['idDistrito'] = $idDistrito;
        $json['codigo_expe_finalizacion'] = trim($cod_expediente, "'");
        // $arrayStr = explode(":",$cod_expediente);
        // $cod_expe_temp = $arrayStr[1];
        // $json['codigo_expe_finalizacion'] = trim($cod_expe_temp, "'");
        $json['flg_tipo'] = $flgTipoLic;

        array_push($arrayLic, $json);
        return $arrayLic;
    }

}
