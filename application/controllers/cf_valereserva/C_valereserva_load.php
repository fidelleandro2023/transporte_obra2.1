<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_valereserva_load extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_valereserva/m_valereserva');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){    	       
           $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
           $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
           $permisos =  $this->session->userdata('permisosArbol');
    	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_VALERESERVA, ID_PERMISO_HIJO_VALERESERVA_LOAD);
    	   $data['opciones'] = $result['html'];
    	   if($result['hasPermiso'] == true){
    	         $this->load->view('vf_valereserva/v_carga_vr',$data);
    	   }else{
    	       redirect('login','refresh');
    	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
      public function dwnFileVRSAP(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/valereservaload/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']); 


            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                  $dataf = $this->readFileVRSAP($uploadfile,PATH_FILE_UPLOAD_REWRITE_VR);   

                if($dataf['error'] == 1){
                    throw new Exception('Ocurrio un problema a tratar de leer el archivo, vuelva a intentarlo o comuniquese con el administrador.');
                }
                if($dataf['countTmp'] != NUM_COLUM_TXT_VALERESERVA_LOAD){
                    throw  new Exception("El archivo no tiene la estructura esperada!");
                }else{
                   $this->session->set_flashdata('rutaFileVRLOAD',PATH_FILE_UPLOAD_REWRITE_VR);
                    $data['msj'] ="El archivo esta procediendo a ser cargado al sistema";
                    $data['error'] = EXIT_SUCCESS;
                }
                              
            } else {
               throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
    /********************************************************** REESCRIBIR ARCHIVO VALE  DE RESERVA EXCEL**************************/

    function readFileVRSAP($inputFile, $outputFile){    
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        
        $trigger = false;
        $initPos = 0;    
        $countTmp = 15;
        $contador=1;
        $indord=1;
       
        
        try{
            $file = fopen($inputFile, "r") or exit("Unable to open file!");        
            $file2 = fopen($outputFile, "w");
      
            while(!feof($file))
            {
                    $linea = fgets($file);
                    $comp = preg_split("/[\t]/", $linea);

                    if ($contador>5){
                       $newlinea="";
                       if (trim($linea)==''){
                            break;
                       }

                      
                        $ctnec=floatval(str_replace('"', '',$comp[9]));
                        $ctred=floatval(str_replace('"', '',$comp[10]));
                        $ctdif=$ctnec-$ctred;

                        $newlinea = $indord."\t".$comp[1]."\t".$comp[2]."\t".$comp[3]."\t".$comp[4]."\t".$comp[5]."\t".$comp[6]."\t".$comp[7]."\t".$comp[8]."\t".$ctnec."\t".$ctred."\t".$ctdif."\t".$comp[11]."\t".$comp[12]."\t".$comp[13]."\t".$comp[14];
                        
                        
                        //   $lote="";


                        // $newlinea = $indord."\t".$comp[4]."\t".$comp[5]."\t".$lote."\t".$comp[1]."\t".$comp[2]."\t".$comp[3]."\t".$comp[6]."\t".$comp[7]."\t".$comp[9]."\t".$comp[10]."\t".$comp[11]."\t".$comp[15]."\t".$comp[12]."\t".$comp[13]."\t".$comp[14];
                        
                        
                        
                        
                        fwrite($file2, trim($newlinea) . PHP_EOL); 
                    
                        $indord++;

                        $contador=6;

                    }else{
                         $contador++;
                    }       
            }
   
            fclose($file2);
            fclose($file);
            $data ['error']= EXIT_SUCCESS;
            $data['countTmp'] = $countTmp;
        }catch(Exception $e){
           
            $data['msj'] = "ERROR LECTURA FILE!";
    }
    return $data;
    }
    

/**************************************************/
   public function uploadVRSAP(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
        
        $data = $this->m_valereserva->getImportValeReservaLoad($this->session->flashdata('rutaFileVRLOAD'));       
        if($data['error']==EXIT_ERROR){
            throw new Exception("ERROR CARGA getImportValeReserva");
        }else{
             $data = $this->m_valereserva->exeUpdateEstadoVR();
              if($data['error']==EXIT_ERROR){
                throw new Exception("ERROR exeUpdateEstadoVRa");
              }
        } 
    
      
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }

//****//
public function creaRepVRWUMAT(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
        $data = $this->m_valereserva->loadInsertWUVRMaterial();
        if($data['error']==EXIT_ERROR){
            throw new Exception("ERROR loadInsertWUVRMaterial");
        }else{
             $data = $this->m_valereserva->loadIdMatPendiente();
              if($data['error']==EXIT_ERROR){
                throw new Exception("ERROR loadIdMatPendiente");
              }else{
                    $data = $this->m_valereserva->loadIdMatNoActivo();
                    if($data['error']==EXIT_ERROR){
                       throw new Exception("ERROR loadIdMatNoActivo"); 
                   }else{
                        $data = $this->m_valereserva->loadIdMatSubproyecto();
                        if($data['error']==EXIT_ERROR){
                            throw new Exception("ERROR loadIdMatNoActivo");
                        }else{
                            $data['msj'] ="Se esta procediendo a cargar la informacion en el extractor.";
                            $this->crearVREstadoExtractor();
                        }
                   }
              }
        } 
    
      
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }

    public function crearVREstadoExtractor(){
        $data['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
                      
            $resultado = $this->m_valereserva->getVRPlanObraNewTabla();
            if($resultado->num_rows()  > 0){
          /*
                 $file = fopen(PATH_FILE_UPLOAD_VR_CON_ESTADO, "w");
                fputcsv($file,  explode('\t',"ITEMPLAN"."\t"."SUBPROYECTO"."\t"."PTR"."\t"."ESTADO PTR"."\t"."CODIGO VR"."\t"."ID MATERIAL"."\t"."DESCRIPCION MATERIAL"."\t"."FECH NEC"."\t"."CANT NEC"."\t"."CANT RED"."\t"."CANT DIF"."\t".
                    "SFIN"."\t"."BOR"."\t"."MOV"."\t"."COSTO MATERIAL"."\t"."TOTAL"."\t"."TOTAL PARCIAL"."\t"."ESTADO"."\t"."OBSERVACION"));
                foreach ($resultado->result() as $row){                    
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->subProyectoDesc."\t". $row->ptr."\t". $row->estadoptr."\t".$row->codigo_vr."\t".$row->id_material."\t".
                        $row->descrip_material."\t". $row->fech_nec."\t". $row->cant_nec."\t". $row->cant_red."\t".$row->cant_dif."\t".
                        $row->sfin."\t". $row->bor."\t". $row->mov."\t". $row->costo_material."\t". $row->total."\t". $row->totalParcial."\t". $row->estadovr."\t".$row->observacion)));}*/
                        $file = fopen(PATH_FILE_UPLOAD_VR_CON_ESTADO, "w");
                fputcsv($file,  explode('\t',"ITEMPLAN"."\t"."SUBPROYECTO"."\t"."ESTADO"."\t"."PTR"."\t"."ESTADO PTR"."\t"."CODIGO VR"."\t"."ID MATERIAL"."\t"."DESCRIPCION MATERIAL"."\t"."FECH NEC"."\t"."CANT NEC"."\t"."CANT RED"."\t"."CANT DIF"."\t".
                    "SFIN"."\t"."BOR"."\t"."MOV"."\t"."COSTO MATERIAL"."\t"."TOTAL"."\t"."TOTAL PARCIAL"."\t"."ESTADO"."\t"."OBSERVACION"));
                foreach ($resultado->result() as $row){                    
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->subProyectoDesc."\t".$row->estadoplandesc."\t". $row->ptr."\t". $row->estadoptr."\t".$row->codigo_vr."\t".$row->id_material."\t".
                        $row->descrip_material."\t". $row->fech_nec."\t". $row->cant_nec."\t". $row->cant_red."\t".$row->cant_dif."\t".
                        $row->sfin."\t". $row->bor."\t". $row->mov."\t". $row->costo_material."\t". $row->total."\t". $row->totalParcial."\t". $row->estadovr."\t".$row->observacion)));
                
                }
                 $data['error']= EXIT_SUCCESS;
                fclose($file);
            }
        }catch (Exception $e){
            $data['msj'] = 'Error interno, al crear archivo de vale de reserva en el extractor';
        }
        return $data;
    }
    
    
    //////////////////////////////17-09-2018/////////////////////////////////
    public function uploadRepVREECCIPMat()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data = $this->m_valereserva->exceLoadRepVR();
        
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    
          
   
}