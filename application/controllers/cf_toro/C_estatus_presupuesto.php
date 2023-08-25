<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_estatus_presupuesto extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->library('lib_utils');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){         
            $data["extra"]='<link href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">
                <link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/><link href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">';
         
            $data["tabla"]= $this->tablaEstatus($this->M_toro->getEstatusPresupuesto());           
            $data["pagina"] = "estatus";
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO, ID_PERMISO_HIJO_ESTATUS_PRESUPUESTO, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_layaout_feix/header',$data);
                $this->load->view('vf_layaout_feix/cabecera');
                $this->load->view('vf_layaout_feix/menu',$data);
                $this->load->view('recursos_feix/js');
                $this->load->view('vf_toro/v_estatus_presupuesto');
                $this->load->view('vf_layaout_sinfix/footer');
                $this->load->view('recursos_feix/fancy',$data);
                $this->load->view('recursos_sinfix/select2');
                $this->load->view('recursos_sinfix/datatable',$data);
                
                $this->load->view('recursos_sinfix/pnotify');
            }else{
                redirect('login','refresh');
            }
           
         }else{
             redirect('login','refresh');
        }
             
    }    
    public function tablaEstatus($datos){
        
         $html='
                <table id="tablaEstatus" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
                    <thead>
                      <tr class="table-primary" style="color:#fff;background-color:#2196F3">                                                     
                          <th>PROYECTO</th>
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROMETIDO</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th> 
                          <th style="width: 5px">PORCENTAJE</th>      
                      </tr>                                                                       
                    </thead>
                <tbody>';
         
        foreach ($datos->result() as $row ) {      
            $color = 'black';
            $back   =   'none';
            if($row->percent<=30){
                $color = 'white';
                $back   =   'green';
            }else if($row->percent<=50){
                $color = 'white';
                $back   =   '#c5c528';
            }else if($row->percent<=100){
                $color = 'white';
                $back   =   'red';
            }
            $html.='<tr> 
                        <td><a href="reporte_toro?pro='.$row->idProyecto.'&&flg_tipo='.$row->flg_tipo.'" target="_blank">'.$row->proyectoDesc.'</a></td>
                        <td>'.number_format($row->presupuesto, 2, '.', ',').'</td>
                        <td>'.number_format($row->reall, 2, '.', ',').'</td>
                        <td>'.number_format($row->comprometido, 2, '.', ',').'</td>    
                        <td>'.number_format($row->planresord, 2, '.', ',').'</td>
                        <td>'.number_format($row->disponible, 2, '.', ',').'</td>
                        <td style="color:'.$color.';background:'.$back.';text-align: center;">'.$row->percent.'%</td>
                   </tr>';
        }
        $html.="</tbody>
            </table>";    
     return $html;                            
    }
    
    public function makeDataToChartLine(){
        
        
        $data = array();
        
        $jsonPadre = array();
        
        $catagorries = array(); 
        
        $list_categorias =  $this->M_toro->getCategoriasByLimitDays(DIAS_PRESUPUESTO_GRAFICO_LINEAS);
        
        foreach($list_categorias->result() as $row){            
            array_push($catagorries, $row->fecha_registro);
        }
   
        $series = array();
        
        $lista_series = $this->M_toro->getSeriesBySubProyectoAndLimitDays(DIAS_PRESUPUESTO_GRAFICO_LINEAS);
        foreach($lista_series->result() as $row){
            //$serie_1 = array();
            $info = $this->M_toro->getSeriesByProyecto($row->descripcion, DIAS_PRESUPUESTO_GRAFICO_LINEAS);
            $dataSerie = array();
            foreach($info->result() as $row){                
                array_push($dataSerie, intval($row->percent));   
            }
            $serie_1 = array('name'=> $row->descripcion, 'data' => $dataSerie);
            array_push($series, $serie_1);
            
            
        }
            
        $salida = array('categorias' => $catagorries, 'serie' =>$series);
        echo json_encode($salida);
        
    }
    
    function filtrarFase(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $fase = $this->input->post('fase');
            if($fase    ==  2018){
                $data["tabla"]= $this->tablaEstatus2018($this->M_toro->getEstatusPresupuesto2018());                
            }else if($fase    ==  2019){
                $data["tabla"]= $this->tablaEstatus2018($this->M_toro->getEstatusPresupuesto2019());                
            }else{//cualquier otro 2012
                $data["tabla"]= $this->tablaEstatus($this->M_toro->getEstatusPresupuesto());
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function tablaEstatus2018($datos){
    
        $html='
                <table id="tablaEstatus" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
                    <thead>
                      <tr class="table-primary" style="color:#fff;background-color:#2196F3">
                          <th>PROYECTO</th>
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROMETIDO</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th>
                          <th style="width: 5px">PORCENTAJE</th>
                      </tr>
                    </thead>
                <tbody>';
         
        foreach ($datos->result() as $row ) {
            $color = 'black';
            $back   =   'none';
            if($row->percent<=30){
                $color = 'white';
                $back   =   'green';
            }else if($row->percent<=50){
                $color = 'white';
                $back   =   '#c5c528';
            }else if($row->percent<=100){
                $color = 'white';
                $back   =   'red';
            }
            $html.='<tr>
                        <td>'.$row->descripcion.'</td>
                        <td>'.number_format($row->presupuesto, 2, '.', ',').'</td>
                        <td>'.number_format($row->reall, 2, '.', ',').'</td>
                        <td>'.number_format($row->comprometido, 2, '.', ',').'</td>
                        <td>'.number_format($row->planresord, 2, '.', ',').'</td>
                        <td>'.number_format($row->disponible, 2, '.', ',').'</td>
                        <td style="color:'.$color.';background:'.$back.';text-align: center;">'.$row->percent.'%</td>
                   </tr>';
        }
        $html.="</tbody>
            </table>";
        return $html;
    }
    
    public function makeDataToChartLine2018(){
    
    
        $data = array();
    
        $jsonPadre = array();
    
        $catagorries = array();
    
        $list_categorias =  $this->M_toro->getCategoriasByLimitDays2018(DIAS_PRESUPUESTO_GRAFICO_LINEAS);
    
        foreach($list_categorias->result() as $row){
            array_push($catagorries, $row->fecha_registro);
        }
         
        $series = array();
    
        $lista_series = $this->M_toro->getSeriesBySubProyectoAndLimitDays2018(DIAS_PRESUPUESTO_GRAFICO_LINEAS);
        foreach($lista_series->result() as $row){
            //$serie_1 = array();
            $info = $this->M_toro->getSeriesByProyecto2018($row->descripcion, DIAS_PRESUPUESTO_GRAFICO_LINEAS);
            $dataSerie = array();
            foreach($info->result() as $row){
                array_push($dataSerie, intval($row->percent));
            }
            $serie_1 = array('name'=> $row->descripcion, 'data' => $dataSerie);
            array_push($series, $serie_1);
    
    
        }
    
        $salida = array('categorias' => $catagorries, 'serie' =>$series);
       echo json_encode($salida);
    
    }
    
	public function makeDataToChartLine2019(){
    
    
        $data = array();
    
        $jsonPadre = array();
    
        $catagorries = array();
    
        $list_categorias =  $this->M_toro->getCategoriasByLimitDays2019(DIAS_PRESUPUESTO_GRAFICO_LINEAS);
    
        foreach($list_categorias->result() as $row){
            array_push($catagorries, $row->fecha_registro);
        }
         
        $series = array();
    
        $lista_series = $this->M_toro->getSeriesBySubProyectoAndLimitDays2019(DIAS_PRESUPUESTO_GRAFICO_LINEAS);
        foreach($lista_series->result() as $row){
            //$serie_1 = array();
            $info = $this->M_toro->getSeriesByProyecto2019($row->descripcion, DIAS_PRESUPUESTO_GRAFICO_LINEAS);
            $dataSerie = array();
            foreach($info->result() as $row){
                array_push($dataSerie, intval($row->percent));
            }
            $serie_1 = array('name'=> $row->descripcion, 'data' => $dataSerie);
            array_push($series, $serie_1);
    
    
        }
    
        $salida = array('categorias' => $catagorries, 'serie' =>$series);
        echo json_encode($salida);
    
    }
	
    function filtrarFaseGrafico(){
            $fase = $this->input->post('fase');
            if($fase    ==  2018){               
                return $this->makeDataToChartLine2018();
            }else if($fase    ==  2019){               
                return $this->makeDataToChartLine2019();
            }else{//cualquier otro 2020                
                return $this->makeDataToChartLine();
            }
    }
}