<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class C_downloadEvidenciasS3 extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->CI =& get_instance();
        $this->CI->config->load('s3', TRUE);
        $this->s3_config = $this->CI->config->item('s3'); 
    }
    function fileExistS3(){
        echo 'exit';
    }
    function zipItemPlanS3() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $Bucket => $this->s3_config['bucket'];
            $objects = $s3->getIterator('ListObjects', array(
                'Bucket' => $bucket, 
            ));
            foreach ($objects as $object) {
                $contents = file_get_contents("s3://{$bucket}/{$object['Key']}");
                $zip->addFromString($object['Key'], $contents);
            }
            foreach ($files as $key => $file) { 
                $result = $s3->putObject([
                    'Bucket' => $this->s3_config['bucket'],
                    'Key'    => 'obras2.1/uploads/evidencia_fotos/'.$itemPlan.'/'.$file['name'],
                    'SourceFile' => $file['tmp_name']		
                ]);
                echo $result->get('ObjectURL');; exit;
                echo '<pre>';
                print_r($result);
                echo '</pre>';
            }
            $itemPlan = $this->input->post('itemPlan');
            if($itemPlan == null) {
                throw new Exception('accion no permitida');
            }
            $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan;
            $ubicacionZip = 'uploads/evidencia_zip/'.$itemPlan;
            if(!is_dir($ubicacionZip)) {
                mkdir($ubicacionZip, 0777);
            }

            if(is_dir($ubicacion)) {
                if (is_dir($ubicacionZip)) {
                    $this->rrmdir($ubicacionZip);
                    mkdir($ubicacionZip, 0777);
                    
                    $fechaActual = $this->fechaActual();
                    $this->zip->read_dir($ubicacion,false);
                    $fileName = $itemPlan.'_fe_'.date("d_m").'.zip';
                    $this->zip->archive($ubicacionZip.'/'.$fileName);
                }
                $data['directorioZip'] =  $ubicacionZip.'/'.$fileName;
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}