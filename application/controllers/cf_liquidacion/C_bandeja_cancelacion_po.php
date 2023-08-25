<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_cancelacion_po extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_liquidacion');
        $this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            $data['listaEECC'] = $this->m_utils->getAllEECC();
        	$data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->m_liquidacion->getPOPreCancelados(null,null,null,null));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_CANCEL_PO);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_CANCEL_PO, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_liquidacion/v_bandeja_cancelacion_po', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function cancelarPO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $codigoPO = $this->input->post('codigoPO') ? $this->input->post('codigoPO') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;


            $idUsuario = $this->session->userdata('idPersonaSession');

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }
            if($itemplan == null || $codigoPO == null || $idEstacion == null){
                throw new Exception('Hubo un error al cargar los datos!!');
            }

            $arrayUpdatePPO = array(
                "estado_po" => 8
            );
	
            $data = $this->m_detalle_obra->updatePO($itemplan,$codigoPO,$idEstacion,$arrayUpdatePPO);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLOGPPO = array(
                    "codigo_po" => $codigoPO,
                    "itemplan" => $itemplan,
                    "idUsuario" => $idUsuario,
                    "fecha_registro" => $this->fechaActual(),
                    "idPoestado" => 8,
                    "controlador" => 'C_bandeja_cancelacion'
                );
                $data = $this->m_detalle_obra->insertarLOGPO($arrayInsertLOGPPO);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayUpdatePOCancelar = array(
                        "idPoestado" => 8,
                        "fecha_cancelacion" => $this->fechaActual(),
                        "id_usuario_cance" => $idUsuario
                    );
        
                    $data = $this->m_detalle_obra->updatePOCancelar($itemplan,$codigoPO,$arrayUpdatePOCancelar);
                    if($data['error'] == EXIT_SUCCESS){
                        $this->db->trans_commit();
                        $data['tablaConsulta'] = $this->makeHTLMTablaConsulta($this->m_liquidacion->getPOPreCancelados(null,null,null,null));
                    }
                }
            }
           

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listPOPreCancel)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>PO</th>
                            <th>ITEMPLAN</th>
                            <th>MOTIVO</th>
                            <th>OBSERVACION</th>
                            <th>USU.PRECAN.</th>
                            <th>FECHA DE REGISTRO</th>
                            <th>SUB PROY</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>FASE</th>
                            <th>AREA</th>
                            <th>TIPO</th>
                            <th>VALOR MAT</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listPOPreCancel != '') {
            foreach ($listPOPreCancel as $row) {

                $html .= '
                        <tr>
                            <td> <a data-itemplan="' . $row->itemplan . '"  data-codigopo="' . $row->ptr . '" data-estacion="' . $row->idEstacion . '" onclick="openModalAlertCancelPO(this)" style="margin-left: 30%;"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/cancelar_equis.png"></a></td>
                            <td>' . $row->ptr . '</td>
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->motivo . '</td>
                            <td>' . $row->observacion . '</td>
                            <td>' . $row->usua_precancela . '</td>
                            <td>' . $row->fecCrea . '</td>
                            <td>' . $row->subProy . '</td>
                            <td>' . $row->zonal . '</td>
                            <th>' . $row->eecc .'</th>
                            <td>' . $row->fase_desc . '</td>
                            <td>'.utf8_decode($row->area_desc).'</td>
                            <td>'.utf8_decode($row->desc_area).'</td>
                            <td>'.$row->valor_material.'</td>
                            <td>'.$row->estado.'</td>
                            ';
            }
            $html .= '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }

        return utf8_decode($html);
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $idSubProyecto = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $idFase = $this->input->post('idFase');
			
			$idSubProyecto  = ($idSubProyecto  == '') ? NULL : $idSubProyecto;
            $eecc		    = ($eecc == '') 		  ? NULL : $eecc;
            $zonal		    = ($zonal    == '') 	  ? NULL : $zonal;
            $idFase		    = ($idFase     == '') 	  ? NULL : $idFase;
			
            $data['tablaConsulta'] = $this->makeHTLMTablaConsulta($this->m_liquidacion->getPOPreCancelados($idSubProyecto, $eecc, $idFase, $zonal));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
