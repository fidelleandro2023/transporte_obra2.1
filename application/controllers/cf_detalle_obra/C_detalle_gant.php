<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_detalle_gant extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_detalle_obra/m_detalle_gant');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){

               $item = (isset($_GET['item']) ? $_GET['item'] : '');
               $idProyecto = $this->m_detalle_gant->getProyectoByItemplan($item);
               $days_diseno = 15;//DIAS POR DEFECTO
               if($idProyecto   ==  ID_PROYECTO_SISEGOS){
                   $days_diseno = 4;
               }
               $infoItem = $this->m_detalle_gant->getInfoGantItemplan($item, $days_diseno);
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $data['itemplan']  = $item;
               $dataItem = $this->m_utils->getCentralYECCByItemplan($item);
               $data['dataToGant']  = $this->getDataToGant($item, $infoItem, $dataItem, $days_diseno);
               $permisos =  $this->session->userdata('permisosArbol');
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
               $data['opciones'] = $result['html'];

               $this->load->view('vf_detalle_obra/v_detalle_gant',$data);

         }else{
             redirect('login','refresh');
        }

    }
    
   function getDataToGant($itemplan, $infoItem, $dataItem, $days_diseno){
        $final['data'] = array();
        //ITEMPLAN INFO
        $temp1 = array( 'id' => '1', 'avance' => '0', 'time' => 68, 'text' => $itemplan,  'color' => 'green',
            'holder' => $dataItem['empresaColabDesc'].' - '.$dataItem['jefatura'], 'start_date' => $infoItem['fecha_inicio_diseno'], 'duration' => (($infoItem['planobra_real']!=null) ? $infoItem['planobra_real'] : $infoItem['planobra_real_tmp']),
            'progress'=> (intval(68)/intval((($infoItem['planobra_real']!=null) ? $infoItem['planobra_real'] : (($infoItem['planobra_real_tmp']!=null ? $infoItem['planobra_real_tmp'] : 1))))), 'open' => true
        );
        array_push($final['data'], $temp1);

        //DISENO
        $temp1 = array( 'id' => '2', 'avance' => '0', 'time' => $days_diseno, 'text' => (($infoItem['fecha_ejecucion']!=null) ? '(*) ' : '' ).'DISENO',  'color' => '#ff8e00fa',
            'holder' => '', 'start_date' => $infoItem['fecha_inicio_diseno'], 'duration' => (($infoItem['dif_diseno_real'] != null) ? $infoItem['dif_diseno_real'] : $infoItem['dif_diseno_tmp']),
            'progress'=> (intval(7)/intval((($infoItem['dif_diseno_real'] != null) ? (( $infoItem['dif_diseno_real'] == 0) ? 1 : $infoItem['dif_diseno_real']) : (($infoItem['dif_diseno_tmp']!=null ? $infoItem['dif_diseno_tmp'] : 1))))), 'open' => true, 'parent' => 1
        );
        array_push($final['data'], $temp1);
        
        //APROBACION
        $temp1 = array( 'id' => '3', 'avance' => '0', 'time' => 1, 'text' => (($infoItem['fecha_aprobacion_real']!=null) ? '(*) ' : '' ).'APROBACION VR', 'color' => '#ff8e00fa',
            'holder' => '', 'start_date' => $infoItem['fecha_inicio_aprobacion'], 'duration' => (($infoItem['dif_aprobacion_real'] != null) ? $infoItem['dif_aprobacion_real'] : $infoItem['dif_aprobacion_tmp']),
            'progress'=> (intval(1)/intval((($infoItem['dif_aprobacion_real'] != null) ? (( $infoItem['dif_aprobacion_real'] == 0) ? 1 : $infoItem['dif_aprobacion_real']) : (($infoItem['dif_aprobacion_tmp']!=null ? $infoItem['dif_aprobacion_tmp'] : 1))))), 'open' => true, 'parent' => 1
        );
        array_push($final['data'], $temp1);
        
        //OPERACION
        $temp1 = array( 'id' => '4', 'avance' => '0', 'time' => 60, 'text' => (($infoItem['fecha_preliquidacion_real']!=null) ? '(*) ' : '' ). 'OPERACION', 'color' => '#ff8e00fa',
            'holder' => '', 'start_date' => $infoItem['fecha_inicio_operacion'], 'duration' => (($infoItem['dif_operacion_real'] != null) ? $infoItem['dif_operacion_real'] : $infoItem['dif_operacion_tmp']),
            'progress'=> (intval(60)/intval((($infoItem['dif_operacion_real'] != null) ? $infoItem['dif_operacion_real'] : (($infoItem['dif_operacion_tmp']!=null ? $infoItem['dif_operacion_tmp'] : 1))))), 'open' => true, 'parent' => 1
        );
        array_push($final['data'], $temp1);
        
        
        $tasks = $this->m_detalle_gant->getTaskByItemPlan($itemplan);
        
        foreach($tasks as $row){
            $temp1 = array( 'id' => $row->id_gant, 'avance' => $row->avance, 'time' => 0, 'text' => $row->descripcion,
                'holder' => $row->responsable, 'start_date' => $row->fecha_inicio, 'duration' => $row->duracion,
                'progress'=> $row->progreso, 'open' => true,  'textColor'   =>  $row->color_texto, 'color' => $row->color_fondo, 'parent' => $row->pariente 
            );
            array_push($final['data'], $temp1);
        }
               
       $final['links'] = array();
        $links  =   $this->m_detalle_gant->getLinksByItemplan($itemplan);
        foreach($links  as $row){
            $temp2 = array(
                "id" => $row->id_link, "source" => $row->source, "target" => $row->target, "type"=> $row->type
            );        
            array_push($final['links'], $temp2);
        }  
        
        $myJSON = json_encode($final);
        return $myJSON;
    }
    
    function saveTareaGant(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan    = $this->input->post('item');
            $datos       = $this->input->post('datos');            
            $id_gant     = $datos['id'];
            $duracion    = $datos['duration'];
            $holder      = $datos['holder'];
            $avance      = $datos['avance'];
            $fechaIni    = $datos['start_date'];
            $pariente    = $datos['parent'];
            $descripcion = $datos['text'];
            $color       = $datos['color'];
            $colorTxt    = $datos['textColor'];
            
            $ini_fec    = $this->input->post('fecha_ini'); 

            $infoTarea =  array(
                        'itemplan'      => $itemplan,
                        'id_gant'       => $id_gant,
                        'avance'        => $avance,
                        'tiempo_estimado' =>  null,
                        'responsable'   => $holder,
                        'fecha_inicio'  => $ini_fec,
                        'duracion'      => $duracion,
                        'progreso'      => null,
                        'pariente'      => $pariente,
                        'descripcion'   => $descripcion,
                        'color_fondo'   => $color,
                        'color_texto'   => $colorTxt
            );
            
            $this->m_detalle_gant->saveTareaGant($infoTarea);
           // $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));        
    }
    
    function deleteTareaGant(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('item');
            $datos      = $this->input->post('datos');
            $id_gant    = $datos['id'];           
               
            $this->m_detalle_gant->deleteTaskGant($itemplan, $id_gant);
            // $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
    }   
    
      function updateTareaGant(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('item');
            $datos      = $this->input->post('datos');
            $id_gant    = $datos['id'];
            $duracion   = $datos['duration'];
            $holder     = $datos['holder'];
            $avance     = $datos['avance'];
            $pariente   = $datos['parent'];
            $descripcion = $datos['text'];
            $progreso   = $datos['progress'];
            $color       = $datos['color'];
            $colorTxt    = $datos['textColor'];
            
            $ini_fec    = $this->input->post('fecha_ini');

            $infoTarea =  array(            
                'avance'        => $avance,
                'tiempo_estimado' =>  null,
                'responsable'   => $holder,
                'fecha_inicio'  => $ini_fec,
                'duracion'      => $duracion,
                'progreso'      => $progreso,
                'pariente'      => $pariente,
                'descripcion'   =>  $descripcion,
                'color_fondo'   => $color,
                'color_texto'   => $colorTxt
            );
            
            $this->m_detalle_gant->updateTareaGant($itemplan, $id_gant, $infoTarea);
            // $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveLinkGant(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan  = $this->input->post('item');
            $datos     = $this->input->post('datos');
            $idLink    = $datos['id'];
            $source    = $datos['source'];
            $target    = $datos['target'];
            $type      = $datos['type'];            

            $infoLink =  array(
                'id_link'       => $idLink,
                'source'        => $source,
                'target'        => $target,
                'type'          => $type,
                'itemplan'      => $itemplan,
                'fec_registro'  => date("Y-m-d H:m:i"),
                'usua_registro' => $this->session->userdata('userSession')
            );
            
            $this->m_detalle_gant->saveLinkGant($infoLink);
            // $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
    }
    
    function deleteLinkGant(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('item');
            $datos      = $this->input->post('datos');
            $id_link    = $datos['id'];
            
            $this->m_detalle_gant->deleteLinkGant($itemplan, $id_link);
            // $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
    } 
}