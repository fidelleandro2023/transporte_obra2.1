<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_sisego extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_reporte_gerente/m_reporte_sisego');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $data['tablaReporte'] = $this->getTablaReporteSisego(null, null);
        $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
        $data['listaEECC']      = $this->m_utils->getAllEECC();

        $this->load->view('vf_reporte_gerente/v_reporte_sisego', $data);
    }

    function getTablaReporteSisego($idJefatura, $idEmpresaColab) {
        $arrayData = $this->m_reporte_sisego->getTablaReporteSisego($idJefatura, $idEmpresaColab);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>NOMBRE PROYECTO</th>
                            <th>ESTADO</th>
                            <th>FECHA PRELIQUIDACI&Oacute;N</th>
                            <th>EECC</th>
                            <th>JEFATURA</th>
                            <th>OPERADOR</th>
                            <th>DURACIÓN</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($arrayData as $row){     
                    $btnDiseno = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi zmdi-file-plus" data-itemplan="'.$row['itemplan'].'" title="Revertir" onclick="openModalDisenoReporte($(this))"></i>';          
                    $html .=' <tr>
                                <td>'.$btnDiseno.'</td>
                                <td>'.$row['itemplan'].'</td>
                                <td>'.utf8_decode($row['subproyectoDesc']).'</td>
                                <td>'.utf8_decode($row['nombreProyecto']).'</td>
                                <td>'.utf8_decode($row['estadoPlanDesc']).'</td>
                                <td>'.$row['fechaPreliquidacion'].'</td>
                                <td>'.utf8_decode($row['empresaColabDesc']).'</td>
                                <td>'.utf8_decode($row['jefatura']).'</td>
                                <td>'.$row['operador'].'</td>
                                <td>'.$row['duracion'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function filtrarTablaBandejaReporteSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;

        try{ 
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');

            $idEmpresaColab = ($idEmpresaColab == '') ? NULL : $idEmpresaColab;
            $idJefatura     = ($idJefatura == '')     ? NULL : $idJefatura;
            
            $data['tablaBandeja'] = $this->getTablaReporteSisego($idJefatura, $idEmpresaColab);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function openModalDisenoReporte() {
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');

            $data['tablaDiseno'] = $this->getTablaDiseno($itemplan);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    function getTablaDiseno($itemplan) {
        $arrayData = $this->m_reporte_sisego->getTablaDiseno($itemplan);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ESTACI&Oacute;N</th>
                            <th>FECHA ADJUDICACI&Oacute;N</th>
                            <th>FECHA EJECUCI&Oacute;N</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($arrayData as $row){     
                    //$btnModalArchivo = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi zmdi-file-plus" data-itemplan="'.$row['itemplan'].'" title="Revertir" onclick="openModalArchivo($(this))"></i>';          
                    $html .=' <tr>
                                <td>'.$row['estacionDesc'].'</td>
                                <td>'.$row['fecha_adjudicacion'].'</td>
                                <td>'.$row['fecha_ejecucion'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }
}