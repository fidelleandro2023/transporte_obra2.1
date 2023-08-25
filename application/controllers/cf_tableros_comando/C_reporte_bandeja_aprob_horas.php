<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_bandeja_aprob_horas extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tableros_comando/m_reporte_bandeja_aprob_horas');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
               
               $data['nombreUsuario']   =  $this->session->userdata('usernameSession');
               $data['perfilUsuario']   =  $this->session->userdata('descPerfilSession');
               $data['tablaReporteBA']  =  $this->makeHTLMTablaDetalletecnico($this->m_reporte_bandeja_aprob_horas->getReporteBandejaProbHoras());
               $permisos                =  $this->session->userdata('permisosArbol');
               $result                  =  $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_HIJO_TABLERO_COMANDO, ID_PERMISO_HIJO_TAB_COMANDO_BA_1);
               $data['opciones']        =  $result['html'];
               if($result['hasPermiso'] == true){
                     $this->load->view('vf_tableros_comando/v_reporte_bandeja_aprob_horas',$data);
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
    function drawPieBADet(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $estado     = $this->input->post('estado');
            $rango      = $this->input->post('rango');
            log_message('error', $estado.' '.$rango);
            $jsonArray = array();
        
            $litaDatos = $this->m_reporte_bandeja_aprob_horas->getDataToPieReportBA($estado, $rango);
            foreach($litaDatos as $row){
                $dato1 = array();
                $dato1['name']  = $row->proyectoDesc;
                $dato1['y']     = (float)$row->total;
                array_push($jsonArray, $dato1);
            }
            log_message('error', print_r($jsonArray, true));
            $data['dataPie'] = json_encode($jsonArray);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
   
    
    public function makeHTLMTablaDetalletecnico($listaPTR){
        $total_0_12 = 0;
        $total_13_24 = 0;
        $total_25_48 = 0;
        $total_mayor_48 = 0;
        $total_final = 0;
        $html = '<table id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>SITUACION</th>
                            <th style="text-align: center;">0 A 12</th>
                            <th style="text-align: center;">13 A 24</th>
                            <th style="text-align: center;">25 A 48</th>
                            <th style="text-align: center;">MAYOR A 48</th>
                            <th style="text-align: center;">TOTAL</th>
                        </tr> 
                    </thead>
                    <tbody>';
        foreach($listaPTR as $row){         
            $html .=' <tr>
                            <td>'.$row->estado.'</td>
                            <td style="text-align: center;"><a data-estado="'.$row->estado.'" data-range="1" onclick="openModalPieDetalle(this)">'.$row->d_0_12.'</a></td>
                            <td style="text-align: center;"><a data-estado="'.$row->estado.'" data-range="2" onclick="openModalPieDetalle(this)">'.$row->d_13_24.'</a></td>
                            <td style="text-align: center;"><a data-estado="'.$row->estado.'" data-range="3" onclick="openModalPieDetalle(this)">'.$row->d_25_48.'</a></td>
                            <td style="text-align: center;"><a data-estado="'.$row->estado.'" data-range="4" onclick="openModalPieDetalle(this)">'.$row->d_mayor_48.'</a></td>
                            <td style="text-align: center;">'.$row->total.'</td>
                      </tr>';
            $total_0_12 = $total_0_12 + $row->d_0_12;
            $total_13_24 = $total_13_24 + $row->d_13_24;
            $total_25_48 = $total_25_48 + $row->d_25_48;
            $total_mayor_48 = $total_mayor_48 + $row->d_mayor_48;
            $total_final = $total_final + $row->total;
        }   
        $html .='<tr style="color: white;background: var(--celeste_telefonica);">
                    <td style="text-align: center;">TOTAL</td>
                    <td style="text-align: center;">'.$total_0_12.'</td>
                    <td style="text-align: center;">'.$total_13_24.'</td>
                    <td style="text-align: center;">'.$total_25_48.'</td>
                    <td style="text-align: center;">'.$total_mayor_48.'</td>
                    <td style="text-align: center;">'.$total_final.'</td>
                </tr>
            </tbody>
        </table>';
    
        return $html;
    }
    
    
    public function getTableByFilRepBa(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
        $proyectoDesc = $this->input->post('proyecto');
        $rango = $this->input->post('rango');
        $estado     = $this->input->post('estado'); 
        $dataOS = $this->makeHTLMTablaDetPTRBA($this->m_reporte_bandeja_aprob_horas->getDetallePieByProyecto($estado, $rango, $proyectoDesc));
        $data['tablaBADet'] = $dataOS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaDetPTRBA($listaPTR){
    
       $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>PROYECTO</th>
                            <th>SUBPROYECTO</th>
                            <th>ITEMPLAN</th>
                            <th>INDICADOR</th>
                            <th>PO</th>
                            <th>FEC.PRE PRE APROBACION</th>
                            <th>DIF. HORAS</th>
                            <th>ESTADO</th>
                            <th>FEC PARALIZADO</th>
                        </tr> 
                    </thead>
                    <tbody>';
        foreach($listaPTR as $row){
            $html .=' <tr>
                            <td style="text-align: center;">'.$row->proyectoDesc.'</td>
                            <td style="text-align: center;">'.$row->subProyectoDesc.'</td>
                            <td style="text-align: center;">'.$row->itemplan.'</td>
                            <td style="text-align: center;">'.$row->indicador.'</td>
                            <td style="text-align: center;">'.$row->codigo_po.'</td>
                            <td style="text-align: center;">'.$row->fecha_registro.'</td>
                            <td style="text-align: center;">'.$row->dif_horas.'</td>
                            <td style="text-align: center;">'.$row->estado.'</td>
                            <td style="text-align: center;">'.$row->fecha_paralizado.'</td>
                      </tr>';
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }
    
}