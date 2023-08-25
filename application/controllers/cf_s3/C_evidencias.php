<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

class C_evidencias extends CI_Controller
{
    function __construct(){
        parent::__construct();
    } 
    public function insertEvidence(){  
        $output1 = shell_exec('cd /var/www/html/');
        $output2 = shell_exec('s3cmd ls s3://plandeobrasperu/obras2.1/uploads/evidencia_fotos/ --recursive | cat >> lista_evidencias.txt');
        echo "<pre>$output2</pre>";

        $this->db->query("TRUNCATE itemplan_evidencia_s3;");
        $fp = fopen("/var/www/html/lista_evidencias.txt", "r");

        while (!feof($fp)) {
            $linea = fgets($fp);
            $datos = explode(' ',$linea); 
            if(isset($datos[10]) && $datos[10] != '') {
                $date = $datos[0];
                $size = $datos[8]; 
                $url_s3 = $datos[10];  
                $url = str_replace("s3://plandeobrasperu/", "https://plandeobrasperu.s3.us-east-2.amazonaws.com/", $url_s3);
                $items = explode('/',$url); 
                if(isset($items[6]) && $items[6] != '') {
                    $itemPlan = $items[6];
                    $data = array(
                        'itemplan' => $itemPlan,
                        'fecha_registro' => $date,
                        'size' => $size,
                        'url' => $url,
                    );
                    //var_dump($data); exit;
                    $this->db->insert("itemplan_evidencia_s3",$data);
                }
            }
        }
        fclose($fp);
        echo 'OK';
    }   
}