<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_siom_tecnico extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_reporte_siom_tecnico');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
               
               $data['nombreUsuario']   =  $this->session->userdata('usernameSession');
               $data['perfilUsuario']   =  $this->session->userdata('descPerfilSession');
               $data['listaProyectos']  =  $this->m_utils->getAllProyecto();               
               $data['listaJefaturas']  =  $this->m_utils->getAllJefaturaToSiomReport(); 
               $data['listaEECC']       =  $this->m_utils->getAllEECC();
               $permisos                =  $this->session->userdata('permisosArbol');
               $result                  =  $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_REPORTE_TECNICO_2_SIOM);
               $data['opciones']        =  $result['html'];
               if($result['hasPermiso'] == true){
                     $this->load->view('vf_reportes_v/v_reporte_siom_tecnico',$data);
               }else{
                   redirect('login','refresh');
               }
         }else{
             redirect('login','refresh');
        }
             
    }
    
     public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
        
    /***metodos propios de la clase***/
    function drawPieTecnicos(){
        
        $idProyecto     = $this->input->post('proyecto');
        $idJefatura     = $this->input->post('jefatura');
        $idEECC         = $this->input->post('eecc');
        $tipoTecnico    = $this->input->post('tipoTec');
        $estado         = $this->input->post('estado');
        $fechaInicio    = $this->input->post('fecInicio');
        $fechaFin       = $this->input->post('fecFin');
        log_message('error', $idProyecto.' '.$idJefatura.' '.$idEECC);
        $jsonArray = array();
        
        $num = 0;
        
        $litaDatos = $this->m_reporte_siom_tecnico->getTecnicosByProyecto($idProyecto, $idJefatura, $idEECC, $tipoTecnico, $estado, $fechaInicio, $fechaFin);
        foreach($litaDatos as $row){
         //   log_message('error', 'per:'.$row->tecnico_asignado);
         //   log_message('error', 'utf_per:'.utf8_encode($row->tecnico_asignado));
            //if($num < 2){
                $dato1 = array();
                //$dato1['name']  = utf8_encode($row->tecnico_asignado);
                $dato1['name']  = $row->tecnico_asignado;
                $dato1['y']     = (float)$row->per;
                array_push($jsonArray, $dato1);
                $num = $num + $row->num;
          // }
           // $num++;
        }       
        
          log_message('error', 'OK:'.utf8_encode('CESAR ARTURO SALDAÑA ROJAS'));
        /*
        $dato1 = array();
        $dato1['name']  = 'jose';
        $dato1['y']     = 2.1505;
        array_push($jsonArray, $dato1);
        
        $dato2 = array();
        $dato2['name']  = utf8_encode('CESAR ARTURO SALDAÑA ROJAS');
        $dato2['y']     = 1.0753;
        array_push($jsonArray, $dato2);
        
        log_message('error', print_r($jsonArray, true));*/
        $data['dataPie'] = json_encode($jsonArray);
        $data['totalOS'] = $num;
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    } 
    
    
    public function getMarcadoresByProyectoTecmocp(){
        $idProyecto = $this->input->post('id_proyecto');
        $idJefatura = $this->input->post('jefatura');
        $idEECC     = $this->input->post('eecc');
        $tecnico    = $this->input->post('tecnico_asig');
        $estado    = $this->input->post('estado');
        $fechaInicio    = $this->input->post('fecInicio');
        $fechaFin       = $this->input->post('fecFin');
        //$data = array();
        $markers = array();
        $infoMarkers = array();
        $informacion = $this->m_reporte_siom_tecnico->getObrasByIdProyectoAndTecnico($idProyecto, $idJefatura, $idEECC, $tecnico, $estado, $fechaInicio, $fechaFin);
        foreach($informacion as $row){
            $temp = array($row->itemplan, $row->coordenada_y, $row->coordenada_x);
            array_push($markers, $temp);   
        }
        $data['markers'] = json_encode($markers);
        
        /**data to table**/        
        $dataOS = $this->makeHTLMTablaDetalletecnico($this->m_reporte_siom_tecnico->getObrasByIdProyectoAndTecnicoToTable($idProyecto, $idJefatura, $idEECC, $tecnico, $estado, $fechaInicio, $fechaFin));
        $data['tablaDatoSiom'] = $dataOS;
        //log_message('error', print_r($dataOS, true));
        //$data['info_markers'] = $infoMarkers;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaDetalletecnico($listaPTR){
    
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>CODIGO SIOM</th>
                            <th>FECHA REGISTRO</th>
                            <th>ITEMPLAN</th>
                            <th>COORD X</th>
                            <th>COORD Y</th>
                            <th>ESTACION</th>
                            <th>TECNICO ASIGNADO</th>
                            <th>FEC. ULTIMO ESTADO</th>                            
                            <th>ESTADO</th>
                            <th>TOTAL DIAS</th>
                        </tr> 
                    </thead>
                    <tbody>';
        foreach($listaPTR as $row){         
            $html .=' <tr>
                            <td>'.$row->codigoSiom.'</td>
                            <td>'.$row->fechaRegistro.'</td>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->coordenada_x.'</td>
                            <td>'.$row->coordenada_y.'</td>
                            <td>'.$row->estacionDesc.'</td>
                            <td>'.utf8_decode($row->tecnico_asignado).'</td> 
                            <td>'.$row->fecha_ultimo_estado.'</td>
                            <td>'.$row->ultimo_estado.'</td>
                            <td>'.$row->dif_dias.'</td>
                      </tr>';
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }
}