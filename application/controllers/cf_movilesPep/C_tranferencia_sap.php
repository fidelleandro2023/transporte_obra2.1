<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_tranferencia_sap extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_movilesPep/m_tranferencia_sap');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_TRANFERENCIA_SAP_FIJA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_TRANSFERENCIAS, 279, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_movilesPep/v_tranferencia_sap', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function uploadSj1() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $uploaddir = 'uploads/sap/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

//            chmod($uploadfile, 0777);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
//                chmod($uploadfile, 777);
                $prossFile = $this->readFileSapFija($uploadfile, PATH_FILE_UPLOAD_SAP_FIJA_EDIT);
                if ($prossFile['error'] == EXIT_ERROR) {
                    throw new Exception($prossFile['msj']);
                } else if ($prossFile['error'] == EXIT_SUCCESS) {
                    $data['error'] = EXIT_SUCCESS;
                }
                /* if($dataf['countTmp'] != NUM_COLUM_TXT_SAP_FIJA){
                  throw new Exception("El archivo no tiene la estructura esperada!");
                  }else{
                  $data['error'] = EXIT_SUCCESS;
                  } */
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function readFileSapFija($inputFile, $outputFile) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        $trigger = false;
        $initPos = 0;
        //$countTmp = 0;

        try {
            $file = fopen($inputFile, "r") or exit("Unable to open file!");
            $file2 = fopen($outputFile, "w");

            while (!feof($file)) {
                $linea = fgets($file);
                $comp = preg_split("/[\t]/", $linea);
                log_message('error', 'Ivan more: ' . count($comp));
//                log_message('error', print_r($comp, true));
                if ($trigger) {
                    $this->addRow($initPos, $comp, $file2/* ,$countTmp */);
                } else if (!$trigger) {
                    if (count($comp) == 15) {
                        if (trim($comp[0]) == 1) {
                            //$countTmp = count($comp);
                            $initPos = 0;
                            $this->addRow($initPos, $comp, $file2/* ,$countTmp */);
                            $trigger = true;
                        } else if (trim($comp[1]) == 1) {
                            //$countTmp = count($comp);
                            $initPos = 1;
                            $this->addRow($initPos, $comp, $file2/* ,$countTmp */);
                            $trigger = true;
                        } else if (trim($comp[2]) == 1) {
                            //$countTmp = count($comp);
                            $initPos = 2;
                            $this->addRow($initPos, $comp, $file2/* ,$countTmp */);
//                            log_message('error', $respuesta);
                            $trigger = true;
                        } else if (trim($comp[3]) == 1) {
                            // $countTmp = count($comp);
                            $initPos = 3;
                            $this->addRow($initPos, $comp, $file2/* ,$countTmp */);
                            $trigger = true;
                        }
                    } else {
                        throw new Exception('Contenido de archivo no valido, valide el contenido y vuelva a intentarlo.');
                    }
                }
            }

            fclose($file2);
            fclose($file);
            $data ['error'] = EXIT_SUCCESS;
            // $data['countTmp'] = $countTmp;
        } catch (Exception $e) {
            fclose($file2);
            fclose($file);
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function addRow($initPos, $comp, $file2/* ,$countTmp */) {
        // log_message(print_r($comp, true));
//        log_message('error', 'Ivan more: ' . count($comp));
        if (count($comp) == 15) {
            if ($comp[$initPos] == '1') {
                $_SESSION["padre"] = $comp[$initPos + 1];
            } else if ($comp[$initPos] == '2') {
                $_SESSION["hijo"] = $comp[$initPos + 2];
                $comp[$initPos + 1] = $_SESSION["padre"];
            } else if ($comp[$initPos] == '3') {
                $comp[$initPos + 1] = $_SESSION["padre"];
                $comp[$initPos + 2] = $_SESSION["hijo"];
            }

            $linea = '';

            for ($i = $initPos; $i < count($comp); $i++) {
                $linea .= $comp[$i] . "\t";
            }
//            log_message('error', 'Ivan more: ' . print_r($linea));
            fwrite($file2, trim($linea) . PHP_EOL);
        }
    }

    public function uploadSj2() {
        $data ['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->m_tranferencia_sap->loadDataImportSapFija(PATH_FILE_UPLOAD_SAP_FIJA_EDIT);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function execPresupuestoFuntions() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->m_tranferencia_sap->execFunctionsCargaSap();
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogWU = array(
                    "descripcion" => 'Actualizo carga de SAP V2',
                    "fecha_registro" => $this->fechaActual(),
                );
                $data = $this->m_utils->insertarLOGTransWU($arrayInsertLogWU);
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, comuniquese con el administrador (Exec Fun. Presupuesto)';
        }
        echo json_encode($data);
    }

    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function read_file() {
        $cont = 1;
        //Traer el archivo
        $file = $_FILES['upload']['tmp_name'];
        //Cargar la biblioteca excel
        $this->load->library('excel');
        //Lee el archivo de la ruta
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //Obtener sólo la colección de células
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $arr_data;
        //Extraer a un formato de matriz legible de PHP
        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            //Encabezado / estará en la fila 1 solamente.
            if ($row > 1) {
                $arr_data[$row][$column] = $data_value;
            }
        }

        foreach ($arr_data as $fila) {
            $idPro = $this->m_utils->idProyecto($fila['B']);
//            print_r($idPro.' ------ '.$fila['B']);
            $arrayInsert = array(
                "movilesPepDesc" => $fila['E'],
                "pep2" => $fila['D'],
                "idSubproyecto" => $this->m_utils->idSubProyecto($fila['C'],$idPro),
                "promotorPep" => $this->m_utils->idPromotor($fila['A']),
                "idTipoPep" => 3,
                "fecha_registro" => $this->fechaActual(),
            );
            $this->m_utils->saveOracle($arrayInsert);
            print_r($arrayInsert);
        }
    }

}
