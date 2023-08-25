<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_tranferencia_sap_fija_v2 extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tranferencias/m_tranferencia_sap_fija_v2');
        $this->load->model('mf_plan_obra/m_evaluar_sisego');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_TRANFERENCIA_SAP_FIJA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 321, 322, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            $data['tablaEvaluaPep'] = $this->tablaEvaluaPep();
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_tranferencias/v_tranferencia_sap_fija_v2', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function uploadSj1()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $uploaddir =  'uploads/sap/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $prossFile = $this->readFileSapFija($uploadfile, PATH_FILE_UPLOAD_SAP_FIJA_EDIT);
                log_message('error', print_r($prossFile, true));
                if ($prossFile['error'] == EXIT_ERROR) {
                    throw new Exception($prossFile['msj']);
                } else if ($prossFile['error'] == EXIT_SUCCESS) {
                    $data['error'] = EXIT_SUCCESS;
                }
                /*if($dataf['countTmp'] != NUM_COLUM_TXT_SAP_FIJA){
                    throw new Exception("El archivo no tiene la estructura esperada!");
                }else{
                    $data['error'] = EXIT_SUCCESS;
                }            */
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function readFileSapFija($inputFile, $outputFile)
    {
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
                log_message('error', count($comp));
                log_message('error', print_r($comp, true));
                if ($trigger) {
                    $this->addRow($initPos, $comp, $file2/*,$countTmp*/);
                } else if (!$trigger) {
                    if (count($comp) == NUM_COLUM_TXT_SAP_FIJA) {
                        if (trim($comp[0]) == 1) {
                            //$countTmp = count($comp);
                            $initPos = 0;
                            $this->addRow($initPos, $comp, $file2/*,$countTmp*/);
                            $trigger = true;
                        } else if (trim($comp[1]) == 1) {
                            //$countTmp = count($comp);
                            $initPos = 1;
                            $this->addRow($initPos, $comp, $file2/*,$countTmp*/);
                            $trigger = true;
                        } else if (trim($comp[2]) == 1) {
                            //$countTmp = count($comp);
                            $initPos = 2;
                            $this->addRow($initPos, $comp, $file2/*,$countTmp*/);
                            $trigger = true;
                        } else if (trim($comp[3]) == 1) {
                            // $countTmp = count($comp);
                            $initPos = 3;
                            $this->addRow($initPos, $comp, $file2/*,$countTmp*/);
                            $trigger = true;
                        }
                    } else {
                        throw new Exception('Contenido de archivo no valido, valide el contenido y vuelva a intentarlo.');
                    }
                }
            }

            fclose($file2);
            fclose($file);
            $data['error'] = EXIT_SUCCESS;
            // $data['countTmp'] = $countTmp;
        } catch (Exception $e) {
            fclose($file2);
            fclose($file);
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }


    function addRow($initPos, $comp, $file2/*,$countTmp*/)
    {
        // log_message(print_r($comp, true));
        if (count($comp) == NUM_COLUM_TXT_SAP_FIJA) {
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

            fwrite($file2, trim($linea) . PHP_EOL);
        }
    }

    public function uploadSj2()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->m_tranferencia_sap_fija_v2->loadDataImportSapFija(PATH_FILE_UPLOAD_SAP_FIJA_EDIT);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function execPresupuestoFuntions()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->m_tranferencia_sap_fija_v2->execFunctionsCargaSap();
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

    function tablaEvaluaPep()
    {
        // $dataConsulta = $this->sisego->getTablaAll();
        $dataConsulta = $this->m_evaluar_sisego->getTablaPepConsulta();
        $html = '';

        $html .= '<table id="data-table" class="table table-bordered">
					<thead class="thead-default">
						<tr>
							<th>PEP</th>
							<th>FECHA DE REGISTRO</th>
							<th>ESTADO</th>
						</tr>
					</thead>                    
					<tbody id="tb_body">';

        foreach ($dataConsulta as $row) {
            $html .= '<tr>
						<td>' . $row->pep . '</td>
						<td>' . $row->fecha_registro . '</td>
						<td>' . $row->estado . '</td>
					</tr>';
        }
        $html .= '</tbody></table>';
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
