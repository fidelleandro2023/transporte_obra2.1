<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_siom_tecnico_full extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_reporte_siom_tecnico_full');
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
               $result                  =  $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_REPORTE_TECNICO_1_SIOM);
               $data['opciones']        =  $result['html'];
               if($result['hasPermiso'] == true){
                     $this->load->view('vf_reportes_v/v_reporte_siom_tecnico_full',$data);
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
        
    public function getMarcadoresByFiltros(){
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
        $idProyecto = $this->input->post('proyecto');
        $idJefatura = $this->input->post('jefatura');
        $idEECC     = $this->input->post('eecc');
        $estado    = $this->input->post('estado');
        $fechaInicio    = $this->input->post('fecInicio');
        $fechaFin       = $this->input->post('fecFin');
        $tipoTecnico    = $this->input->post('tipoTec');
        //$data = array();
        $markers = array();
        $infoMarkers = array();
        
        /**data to table**/        
        $dataOS = $this->makeHTLMTablaDetalletecnico($this->m_reporte_siom_tecnico_full->getObrasByIdProyectoAndTecnicoToTable($idProyecto, $idJefatura, $idEECC, $estado, $fechaInicio, $fechaFin, $tipoTecnico));
        $data['tablaDatoSiom'] = $dataOS['html'];
        $litaColores = $dataOS['tecnicoColor'];
        log_message('error', print_r($litaColores, true));
        //$data['info_markers'] = $infoMarkers;
        
        
         $informacion = $this->m_reporte_siom_tecnico_full->getObrasByIdProyectoAndTecnico($idProyecto, $idJefatura, $idEECC, $estado, $fechaInicio, $fechaFin, $tipoTecnico);
         foreach($informacion as $row){
         //$indiceColor = 1;
         $iColor = array_search(utf8_decode($row->tecnico_asignado), array_column($litaColores, 'nombre'));
         //log_message('error', print_r($iColor, true) .' - '.$row->tecnico_asignado.'-'.$litaColores[$iColor]['indice_color']);
         $temp = array($row->itemplan, $row->coordenada_y, $row->coordenada_x, $row->tecnico_asignado, $litaColores[$iColor]['indice_color']);
         array_push($markers, $temp);
         }
         $data['markers'] = json_encode($markers);
         
        $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaDetalletecnico($listaPTR){
        /*PRIMERA ITERACION PARA OBTENER TOTALES*/
        $totalCreados = 0;
        $totalAsignado = 0;
        $totalEjecutando = 0;
        $totalValidando = 0;
        $totalAprobada = 0;
        $total = 0;
        
        $indiceColor = 1;
        $fullTecnicosColores = array();
        foreach($listaPTR as $row){
            $totalCreados = $totalCreados + $row->creada;
            $totalAsignado = $totalAsignado + $row->asignada;
            $totalEjecutando = $totalEjecutando + $row->ejecutando;
            $totalValidando = $totalValidando + $row->validando;
            $totalAprobada = $totalAprobada + $row->aprobada;
            $total = $total + $row->total;           
        }
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>COLOR</th>
                            <th>TECNICO</th>
                            <th>CREADO</th>
                            <th>ASIGNADO</th>
                            <th>EJECUTANDO</th>
                            <th>VALIDANDO</th>
                            <th>APROBADA</th>
                            <th>TOTAL</th>
                            <th>% ASIGNACION</th>
                        </tr> 
                    </thead>
                    <tbody>';
        foreach($listaPTR as $row){         
            
            $colorTecnico = array('nombre' => utf8_decode($row->tecnico_asignado),
                'indice_color' => $indiceColor
            );
            array_push($fullTecnicosColores, $colorTecnico);            
            $html .=' <tr>
                            <td><img src="'.base_url().'public/img/iconos/mapa_tecnicos/icono_mapa_tecnico_'.$indiceColor.'.png" alt="Color Mapa"></td>
                            <td>'.utf8_decode($row->tecnico_asignado).'</td>
                            <td style="text-align: center;">'.$row->creada.'</td>
                            <td style="text-align: center;">'.$row->asignada.'</td>
                            <td style="text-align: center;">'.$row->ejecutando.'</td>
                            <td style="text-align: center;">'.$row->validando.'</td>
                            <td style="text-align: center;">'.$row->aprobada.'</td>
                            <td style="text-align: center;">'.$row->total.'</td>
                            <td style="text-align: center;">'.number_format((($row->total*100)/$total), 2).'%</td>
                      </tr>';
            $indiceColor++;
        }
        $html .=' <tr style="color: white;background: var(--celeste_telefonica);">
                            <td>TOTAL</td>
                            <td></td>
                            <td style="text-align: center;">'.$totalCreados.'</td>
                            <td style="text-align: center;">'.$totalAsignado.'</td>
                            <td style="text-align: center;">'.$totalEjecutando.'</td>
                            <td style="text-align: center;">'.$totalValidando.'</td>
                            <td style="text-align: center;">'.$totalAprobada.'</td>
                            <td style="text-align: center;">'.$total.'</td>
                            <td style="text-align: center;">100%</td>
                      </tr>';
        $html .='</tbody>
                </table>';
        $salida['tecnicoColor'] = $fullTecnicosColores;
        $salida['html'] = $html;
        return $salida;
    }
}