<?php
require_once('vendor/autoload.php');
defined('BASEPATH') or exit('No direct script access allowed');
use Aws\S3\S3Client;

class C_regularizar_evidencia_itemplan_masivo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_pqt_plan_obra/m_regularizar_evidencia_itemplan');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
		$this->CI =& get_instance();
        $this->CI->config->load('s3', TRUE);
        $this->s3_config = $this->CI->config->item('s3'); 
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
		if($logedUser != null){
			$itemplan = (isset($_GET['item']) ? $_GET['item'] : '');
			$idUsuario = $this->session->userdata('idPersonaSession');
			$data['nombreUsuario'] =  $this->session->userdata('usernameSession');
			$data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
			$data['itemplan'] = $itemplan;

			$permisos =  $this->session->userdata('permisosArbolTransporte');
			$result = $this->lib_utils->getHTMLPermisos($permisos, 54, 332, ID_MODULO_PAQUETIZADO);
			$data['opciones'] = utf8_encode($result['html']);
			$data['title'] = 'CARGA DE EVIDENCIA MASIVO MOVIL';
			
			
			if($result['hasPermiso'] == true && in_array($idUsuario,[3,46,294,2317])){
				$this->load->view('vf_pqt_plan_obra/v_regularizar_evidencia_itemplan_masivo',$data);
			}else{
				redirect('login','refresh');
			}
	  }else{
		  redirect('login','refresh');
	 }

    }

	public function validarCarga()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

			$itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
			$idEECC =  $this->session->userdata('eeccSession');

			if($itemplan == null){
				throw new Exception('Debe ingresar el itemplan para filtrar!!');
			}
			if (!isset($idEECC)) {
                throw new Exception('Su sesión ha expirado, ingrese nuevamente!!');
            }
			$data['canLiqui'] = false;
			$arrayInfo = $this->m_regularizar_evidencia_itemplan->getInfoItemplan($itemplan);
			if($arrayInfo == null){
				throw new Exception('El itemplan no cumple lo requisitos para realizar la carga!!');
			}else{
				$rutaArchivo ='uploads/evidencia_fotos/'.$itemplan;
				if (!file_exists($rutaArchivo)) {
					$data['canLiqui'] = true;
					$data['error'] = EXIT_SUCCESS;
				}else{
					throw new Exception('El itemplan ya cuenta con evidencia!!');
				}
			}
			
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

	public function cargarEvidenciaLiquiPIN()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

			$idUsuario = $this->session->userdata('idPersonaSession');
            $usuarioDesc = $this->session->userdata('userSession');

			if (!isset($idUsuario) || !isset($usuarioDesc)) {
                throw new Exception('Su sesión ha expirado, ingrese nuevamente!!');
            }
			if(count($_FILES) == 0){
				throw new Exception('Debe seleccionar un archivo para procesar data!!');
			}
            $arrayTipos = array(
                'application/x-zip-compressed',
				'application/octet-stream',
				'application/zip'
			);
            $fechaActual = $this->fechaActual();
            $countfiles = count($_FILES['files']['name']);
            $countExito = 0;
            $countError = 0;
            $arrayIndice = array();
            $msjError = '';

            for($i = 0;$i < $countfiles;$i++){
                $nombreArchivo = $_FILES['files']['name'][$i];
                $tipoArchivo = $_FILES['files']['type'][$i];
                $nombreArchivoTemp = $_FILES['files']['tmp_name'][$i];
                $tamano_archivo = $_FILES['files']['size'][$i];
                $arryNombreArchivo = explode(".", $nombreArchivo);

                $msjError = '';


                if (!in_array($tipoArchivo, $arrayTipos)) {
                    $msjError = '1';
                }

                $itemplan = $arryNombreArchivo[0];
				
				//_log($tipoArchivo);
				
                $arrayInfo = $this->m_regularizar_evidencia_itemplan->getInfoItemplan($itemplan);
                if($arrayInfo == null){#no existe o no cumple el itemplan
                    $msjError = '1';
                }
                $arrayCount =  $this->m_regularizar_evidencia_itemplan->getCountEvidencia($itemplan);
				// if($arrayCount['cantidad'] > 0){
					// if($arrayCount['cant_subida'] >= 3){# ya tiene evidencia
						// $msjError = '2';
					// }
				// }


                if($msjError != ''){
                    $arrayIndice []= $i;
                    $countError++;
                }else{
					
                    
					$infoEstacion = $this->m_regularizar_evidencia_itemplan->getInfoEstacionByItemplan($itemplan);
					if ($infoEstacion == null) {
                        throw new Exception('Hubo un error al traer la informacion de la estacion!!');
                    }
					
                    $rutaCarpeta ='uploads/evidencia_fotos/'.$itemplan;
					
					
                    if (!file_exists($rutaCarpeta)) {
                        if (!mkdir($rutaCarpeta)) {
                            throw new Exception('Hubo un error al crear la carpeta!!');
                        }
                    }else{
						$this->rrmdir($rutaCarpeta);
						if (!mkdir($rutaCarpeta)) {
							throw new Exception('Hubo un error al crear la carpeta!!');
						}
					}
								
                    $rutaFinalCarpeta = $rutaCarpeta.'/'.$infoEstacion['estacionDesc'];
                    if (!file_exists($rutaFinalCarpeta)) {
                        if (!mkdir($rutaFinalCarpeta)) {
                            throw new Exception('Hubo un error al crear la carpeta!!');
                        }
                    }

                    $rutaFinalArchivo = $rutaFinalCarpeta . '/' .$nombreArchivo;

												
						$s3 = new Aws\S3\S3Client([
						   'region'  => $this->s3_config['region'],
							'version' => 'latest',
							'credentials' => [
								'key'    => $this->s3_config['key'],
								'secret' => $this->s3_config['secret'],
							]
						]);
						
						
						$resp = _enviar_aws_archivo_array($s3, $nombreArchivo, $nombreArchivoTemp, 'obras2.1/'.$rutaFinalCarpeta.'/', $this->s3_config);
					
						if(count($resp) == 0) {
							throw new Exception("No se guardo la evidencia, verificar.");
						}

                    if (move_uploaded_file($nombreArchivoTemp, $rutaFinalArchivo)) {

						if($arrayCount['cantidad'] == 0 || $arrayCount['cantidad'] == null){
							$arrayInsert = array();
							$arrayInsert = array(  
								'itemplan' => $itemplan,
								'file_name' => $rutaFinalArchivo,
								'fecha_registro' => $fechaActual,
								'id_usuario' => $idUsuario,
								'cant_subida' => 1
							);
							$data = $this->m_regularizar_evidencia_itemplan->insertEvidenciaPIN($arrayInsert);
							if($data['error'] == EXIT_ERROR){
								throw new Exception($data['msj']);
							}
						}else{
							$arrayUpdate = array();
							$arrayUpdate = array(  
								'itemplan' => $itemplan,
								'file_name' => $rutaFinalArchivo,
								'fecha_registro' => $fechaActual,
								'id_usuario' => $idUsuario,
								'cant_subida' => ($arrayCount['cant_subida']+1)
							);
							$data = $this->m_regularizar_evidencia_itemplan->updateEvidenciaPIN($arrayUpdate);
							if($data['error'] == EXIT_ERROR){
								throw new Exception($data['msj']);
							}
						}
						

						
						$evidencia1 = $resp[0];
						$evidencia2 = null;
						if(count($resp) > 1) {
							$evidencia2 = $resp[1];
						}							
						 
						$dataFormularioEvidencias = array(
							'itemplan'          => $itemplan,
							'fecha_registro'    => $fechaActual,
							'usuario_registro'  => $idUsuario,
							'idEstacion'        => $infoEstacion['idEstacion'],
							'path_pdf_pruebas'  => $rutaFinalArchivo,
							'path_pdf_perfil'   => $rutaFinalArchivo,
							'url_pdf_pruebas'	=> $evidencia1,
							'url_pdf_perfil'    => $evidencia2
						);
						
						$data = $this->m_regularizar_evidencia_itemplan->registrarEvidencias($dataFormularioEvidencias,$itemplan,$infoEstacion['idEstacion']);
						if($data['error'] == EXIT_ERROR){
                            throw new Exception($data['msj']);
                        }
			
                        $countExito++;
						

                    }else{
                        throw new Exception('No se pudo subir el archivo: ' . $nombreArchivo . ' !!');
                    }
                }
            }
            $data['array_indice_error'] = json_encode($arrayIndice);
			if($countfiles > 1){
				$data['msj'] = 'Se procesaron #'.($countfiles).' archivos. Se cargaron #'.($countExito).' con éxito y  #'.$countError.' no se cargaron.';
			}else{
				if($data['error'] == EXIT_SUCCESS){
					$data['msj'] = 'Se cargó correctamente el archivo.';
				}else{
					if($msjError == '1'){
						$data['msj'] = 'No se pudo cargar el archivo.';
					}else{
						$data['msj'] = 'El archivo ya alcanzo la maxima cantidad de subidas (3).';
					}
				}
			}
            
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
		_log(print_r($data, true));
        echo json_encode($data);
    }

	public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
	
	function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
