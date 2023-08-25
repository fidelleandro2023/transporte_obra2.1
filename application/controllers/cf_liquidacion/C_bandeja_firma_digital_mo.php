<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_firma_digital_mo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_liquidacion/m_bandeja_firma_digital_mo');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $zonas = $this->session->userdata('zonasSession');

            $data['tablaFirmaDigital'] = $this->getTablaFirmaDigital(NULL, NULL, NULL, NULL);
        
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_FIRMA_DIGITAL);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_FIRMA_DIGITAL, ID_MODULO_ADMINISTRATIVO);
            $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
            $data['listaEECC']      = $this->m_utils->getAllEECC();
            $data['opciones'] = $result['html'];

            $this->load->view('vf_liquidacion/v_bandeja_firma_digital_mo', $data);

        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaFirmaDigital($idEmpresaColab, $idJefatura, $fechaInicio, $fechaFin) {
        $cont = 0;
        $arrayData = $this->m_bandeja_firma_digital_mo->getBandejaFirmaDigital($idEmpresaColab, $idJefatura, $fechaInicio, $fechaFin);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>ITEMPLAN</th>
                            <th>PO</th>                     
                            <th>ESTACI&Oacute;N</th>       
                            <th>FECHA VALIDACI&Oacute;N</th>
                            <th>EMPRESA COLAB.</th>
                            <th>JEFATURA</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($arrayData as $row){              
                    $cont++;
                    $html .=' <tr>
                                <th><input type="checkbox" class="checkboxFirma_'.$cont.'" 
                                data-codigo_po="'.$row['codigo_po'].'" data-itemplan="'.$row['itemplan'].'" onchange="getDataInsert($(this),'.$cont.');" />
                                </th>
                                <td>'.$row['itemplan'].'</td>
                                <td>'.$row['codigo_po'].'</td>					
                                <td>'.utf8_decode($row['estacionDesc']).'</td>
                                <td>'.$row['fecha_validacion'].'</td>
                                <td>'.$row['empresaColabDesc'].'</td>
                                <td>'.$row['jefatura'].'</td>
                            </tr>';
                }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function validarFirmaDigital() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arrayData = array();
            $itemplan  = $this->input->post('itemplan');
            $codigo_po = $this->input->post('codigo_po');
            $arraySelectAprob = $this->input->post('arraySelectAprob');
            if(count($arraySelectAprob) == 0) {
                throw new Exception('error, seleccionar');
            }
            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null) {
                throw new Exception('Se termin&oacute; la sesi&oacute;n, recargue la p&aacute;gina.');
            }

            foreach($arraySelectAprob AS $row) {
                $row['flg_firma_dig'] = 1;
                array_push($arrayData, $row);

                $count = $this->m_bandeja_firma_digital_mo->countUsuarioAprob($row['itemplan'], $idUsuario);

                if($count == 0) {
                    throw new Exception('Seleccion&oacute; un itemplan de una jefatura que no tiene a cargo.');
                }
            }

            $data = $this->m_bandeja_firma_digital_mo->updateEstadoPO($arrayData);

            $data['tablaFirmaDigital'] = $this->getTablaFirmaDigital();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaFirmaDigital(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{ 
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');
            $fechaInicio    = $this->input->post('fechaInicio');
            $fechaFin       = $this->input->post('fechaFin');

            $idEmpresaColab = ($idEmpresaColab == '') ? NULL : $idEmpresaColab;
            $idJefatura     = ($idJefatura == '')     ? NULL : $idJefatura;
            $fechaInicio    = ($fechaInicio == '')    ? NULL : $fechaInicio;
            $fechaFin       = ($fechaFin == '')       ? NULL : $fechaFin;
            
            $data['tablaFirmaDigital'] = $this->getTablaFirmaDigital($idEmpresaColab, $idJefatura, $fechaInicio, $fechaFin);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}