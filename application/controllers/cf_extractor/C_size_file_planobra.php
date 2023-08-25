<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_size_file_planobra extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){  	   
	    $logedUser = $this->session->userdata('usernameSession');
        
        if($logedUser != null){
            
               $user = $this->session->userdata('idPersonaSession');
               $zonasUser = $this->session->userdata('zonasSession');
           
               
               $data['listartabla'] = $this->makeHTLMTExtractorInterno();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');   
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_EXTRACTOR_INTERNO);
               $data['opciones'] = $result['html'];
               if($result['hasPermiso'] == true){
                   $this->load->view('vf_extractor/v_extractor_interno',$data);
               }else{
                   redirect('login','refresh');
               }
       }else{
           redirect('login','refresh');
       }
        	 
    } 




    public function makeHTLMTExtractorInterno(){
     
        $html = '
        <div class="card-block">
            <div class="table table-responsive">
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th ><h4 style="text-align:center;">Extractores Internos</h4></th>
                        </tr>
                        <tr>
                            <th>Evidencias ZIP<p>&nbsp</p></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a onclick="generarCSVSIZEFILE();" style="font-size:40px;" class="zmdi zmdi-file-text zmdi-hc-fw"></a>Evidencias</td>
                            
                             
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        ';
                    
        return utf8_decode($html);
    }
   




    public function ObtenerPesoRegistros(){

        $file='uploads/evidencia_zip/';

        $listado=$this->m_utils->getItemplanArchivos();

         if(count($listado->result()) > 0){

                $fileA = fopen(PATH_FILE_UPLOAD_EVIDENCIA_SIZE, "w");
                fputcsv($fileA,  explode('\t',"ITEMPLAN"."\t".
                                    "NOMBRE DEL ARCHIVO"."\t".
                                             "TAMAÑO KB"."\t".
                                   "TAMAÑO PERMITIDO KB"."\t".
                                "ARCHIVO EXCEDE EL TAMAÑO"));
                
                foreach($listado->result() as $row){ 
                    $dir='';
                    $itemplan=$row->itemplan;
                    $dir=$file.$itemplan.'/';

                    if(file_exists($dir)){
                        $directorio=opendir($dir);
                        $conteo=0;
                        while($archivo = readdir($directorio)){ 
                            if($archivo!="." && $archivo!=".."){
     
                                $zip=zip_open($dir.$archivo);

                                if ($zip){
                            
                                    while ($zip_entry=zip_read($zip)){   
                                        if(zip_entry_open($zip, $zip_entry)){
                                            
                                            $contenido=zip_entry_name($zip_entry);
                                            $taman=zip_entry_filesize($zip_entry);
                                            $taman=round($taman/1024,2);
                                            $flag='';
                                            if($taman>TAMANIO_MAX_EVIDENCIA_MB){
                                               $flag='X';
                                            }


                 fputcsv($fileA, explode('\t', utf8_decode($itemplan."\t". 
                                                              $contenido."\t". 
                                                                  $taman."\t".
                                                TAMANIO_MAX_EVIDENCIA_MB."\t".
                                                                    $flag)));

                                            zip_entry_close($zip_entry);
                                        }
                                    }

                                    zip_close($zip);
                                }

                           }
                    
                        }

                        closedir($directorio);

                    }
                }

                fclose($fileA);
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment;filename="'.basename(PATH_FILE_UPLOAD_EVIDENCIA_SIZE).'"');
                readfile(PATH_FILE_UPLOAD_EVIDENCIA_SIZE);
                exit;

         }

        
    }


  
  

    
}