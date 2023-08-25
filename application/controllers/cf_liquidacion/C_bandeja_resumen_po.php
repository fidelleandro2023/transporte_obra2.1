<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_resumen_po extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_resumen_po');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
            $data['listaEECC']      = $this->m_utils->getAllEECC();
            $data['tablaResumenPO'] = $this->getTablaBandejaResumenPO($this->m_bandeja_resumen_po->getBandejaResumenPO(NULL, NULL));
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_RESUMEN_PO);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_RESUMEN_PO, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_liquidacion/v_bandeja_resumen_po',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    public function getTablaBandejaResumenPO($arrayData){
        
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="2" style="text-align: center;"></th>
                            <th colspan="2" style="text-align: center; background-color: palegoldenrod;">0 - 7 D&Iacute;AS</th>
                            <th colspan="2" style="text-align: center; background-color: darksalmon;">7 - 10 D&Iacute;AS</th>
                            <th colspan="2" style="text-align: center; background-color: green;">10 - 30 D&Iacute;AS</th>                            
                            <th colspan="2" style="text-align: center; background-color: lightblue;">> 30 D&Iacute;AS</th>
                        </tr
                        <tr role="row">   
                            <th colspan="1" style="text-align:center">Nro</th> 
                            <th colspan="1" style="text-align:center">SITUACION</th>                                             
                            <th colspan="1" style="text-align:center">Cantidad</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                            <th colspan="1" style="text-align:center">Cantidad</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                            <th colspan="1" style="text-align:center">Cantidad</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                            <th colspan="1" style="text-align:center">Cantidad</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        
        foreach($arrayData as $row){
            $html .=' <tr>
                            <th>'.$row['idFlujo'].'</th>
                            <th>'.$row['situacion'].'</th>
                            <th style="text-align:center"><a style="color:blue;cursor:pointer" data-flujo="'.$row['flujo'].'" data-interval="dia_0_7"    onclick="getModalDetallePO($(this));">'.$row['dia_0_7'].'</a></th>
                            <th>'.$row['total_0_7'].'</th> 
                            <th style="text-align:center"><a style="color:blue;cursor:pointer" data-flujo="'.$row['flujo'].'" data-interval="dia_7_10"   onclick="getModalDetallePO($(this));">'.$row['dia_7_10'].'</a></th>
                            <th>'.$row['total_7_10'].'</th> </th>
                            <th style="text-align:center"><a style="color:blue;cursor:pointer" data-flujo="'.$row['flujo'].'" data-interval="dia_10_30"   onclick="getModalDetallePO($(this));">'.$row['dia_10_30'].'</a></th>
                            <th>'.$row['total_10_30'].'</th> </th>
                            <th style="text-align:center"><a style="color:blue;cursor:pointer" data-flujo="'.$row['flujo'].'" data-interval="dia_30_mas" onclick="getModalDetallePO($(this));">'.$row['dia_30_mas'].'</a></th>
                            <th>'.$row['total_30_mas'].'</th> </th>
                     </tr>';
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    
    function getModalDetallePO(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $flujo    = $this->input->post('flujo');
            $interval = $this->input->post('interval');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura      = $this->input->post('idJefatura');

            $idEmpresaColab = ($idEmpresaColab == '') ? null : $idEmpresaColab;
            $idJefatura     = ($idJefatura     == '') ? null : $idJefatura;

            $data['tablaDetallePO'] = $this->getTablaDetallePO($this->m_bandeja_resumen_po->getDetallePO($flujo, $interval, $idEmpresaColab, $idJefatura));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaDetallePO($arrayData){
        $cont = 0;
        $html = '<table id="data-table2" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                      
                        <tr>
                            <th>Nro</th>
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">ESTACI&Oacute;N</th>
                            <th style="text-align:center">PO</th>
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">JEFATURA</th>
                            <th style="text-align:center">FECHA</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($arrayData as $row){
            $cont++;
            $html .=' <tr>
                            <td>'.$cont.'</td>
                            <td>'.$row['itemplan'].'</td>
                            <td>'.$row['estacionDesc'].'</td>  
                            <td>'.$row['ptr'].'</td>
                            <td>'.$row['empresaColabDesc'].'</td>
                            <td>'.$row['jefatura'].'</td>                                                  
                            <td>'.$row['fecha'].'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }      

    function filtrarTablaResumen(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{ 
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');

            $data['tablaResumenPO'] = $this->getTablaBandejaResumenPO($this->m_bandeja_resumen_po->getBandejaResumenPO($idEmpresaColab, $idJefatura));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}