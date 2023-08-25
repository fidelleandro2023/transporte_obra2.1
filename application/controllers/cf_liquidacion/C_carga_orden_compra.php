<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_carga_orden_compra extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_carga_orden_compra');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $eecc = '';            
            $idEECC = $this->session->userdata('eeccSession');            
            if($idEECC  ==  ID_EECC_COBRA){
                $eecc   =   'COBRA';
            }else if($idEECC  ==  ID_EECC_LARI){
                $eecc   =   'LARI';
            }else if($idEECC  ==  ID_EECC_DOMINION){
                $eecc   =   'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.';             
            }else if($idEECC  ==  ID_EECC_EZENTIS){
                $eecc   =   'CALATEL';                
            }            
            $data['eecc']    =   $eecc;
           // $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_carga_orden_compra->getBandejaAlarmasMO($eecc));
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_CARGA_MASIVA_OC);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_CARGA_MASIVA_OC, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_liquidacion/v_carga_orden_compra',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    
    public function uploadFileOC(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/orden_compra/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);
                if(count($comp)==CANTIDAD_COLUMNAS_ORDEN_COMPRA_TXT){
    
                    $dataFileUpload = $this->readDataFromFile($uploadfile);
                    $info_2 = $this->makeHTMLBodyTable($dataFileUpload);
                    $data['tablaData'] = $info_2['html'];
                    $data['jsonDataFIleValido'] = json_encode(array_map('utf8_encode', $info_2['array']));
                    $data['jsonDataFIle'] = json_encode(array_map('utf8_encode', $dataFileUpload));
                    $data['error'] = EXIT_SUCCESS;
    
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (12 columnas separados por tabulaciones.)');
                }
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
  
    public function readDataFromFile($uploadfile){
        $arra = array();
        $html = '';
        $fp = fopen($uploadfile, "r");
        while(!feof($fp)) {
            $linea = fgets($fp);
            $datos= preg_split("/[\t]/", $linea);
            if(count($datos)==CANTIDAD_COLUMNAS_ORDEN_COMPRA_TXT){
                array_push($arra, $linea);
            }
        }
        fclose($fp);
        return $arra;
    }
    
    public function makeHTMLBodyTable($listaDatos){
        $data['html'] = '';
        $data['array'] = '';
        $html = '';
        $indice = 0;
        $cont_indice_valido = 0;        
        $array_valido = array();
        foreach ($listaDatos as $linea){
            $indice_valido = '';
            $datos= preg_split("/[\t]/", $linea);
            $infoPtr = $this->m_carga_orden_compra->getInfoPtr(trim($datos[0]));
           
            if($infoPtr!=null){
                if($infoPtr['hoja_gestion']!=null){
                    if($infoPtr['orden_compra']!=null){
                        $color_row = "orange";
                        $situacion = 'ACTUALIZAR';
                        array_push($array_valido, $linea);
                        $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                        $cont_indice_valido++;
                    }else{
                        $color_row = "green";
                        $situacion = 'OK';
                        array_push($array_valido, $linea);
                        $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                        $cont_indice_valido++;
                    }
                    
                }else{
                    $color_row = "#b3b37b";
                    $situacion = 'SIN H.G';
                }
            }else{
                $color_row = "red";//DATO INVALIDO O PTR NO ENCONTRADA
                $situacion = 'NO ENCONTRADO';
            }
            
            $html .= '<tr id="tr'.$indice.'" style="background-color:'.$color_row.'">
                        <th style="width: 5px;"><a style="cursor:pointer;" '.$indice_valido.' data-indice="'.$indice.'" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                    	<th style="color:white">'.$datos[0].'</th>
                    	<th style="color:white">'.$datos[1].'</th>
                    	<th style="color:white">'.$datos[2].'</th>
                	    <th style="color:white">'.$situacion.'</th>
                	</tr>';
            $indice++;
        }
        $data['html'] = $html;
        $data['array'] = $array_valido;
        return $data;
    }
    
    
    public function saveOC(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $jsonDataFile   = $this->input->post('jsonDataFile');
            $arrayFile = json_decode($jsonDataFile);
            $arrayFinal = array();
            if($arrayFile!=null){
                foreach($arrayFile as $linea){
                    if($linea!=null){
                        $datos= preg_split("/[\t]/", $linea);
                        $dataCMO = array();
                        $dataCMO['ptr']                 = trim($datos[0]);
                        $dataCMO['orden_compra']        = trim($datos[1]);
                        $dataCMO['nro_certificacion']   = trim($datos[2]);
                        $dataCMO['estado']   = CERTIFICACION_MO_CON_ORDEN_COMPRA;   
                        $dataCMO['usua_reg_oc']         = $this->session->userdata('userSession');
                        $dataCMO['fec_reg_oc']          = date("Y-m-d");
                        array_push($arrayFinal, $dataCMO);
                    }
                }
            } 
            $data = $this->m_carga_orden_compra->liquidarOCptrCertificacion($arrayFinal);
            $data['error']    = EXIT_SUCCESS;        
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}