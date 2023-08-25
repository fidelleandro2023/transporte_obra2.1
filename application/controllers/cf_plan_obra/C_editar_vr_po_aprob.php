<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_editar_vr_po_aprob extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_liquidacion/M_editar_vr_po');
        $this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc = $this->session->userdata('eeccSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 228, ID_PERMISO_HIJO_EDITAR_VR_PO, 3);
            $data['title'] = 'EDITAR VALE RESERVA PO APROBADA';
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_liquidacion/v_editar_vr_po_aprob', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getComboPtr()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $cmbPtr = null;
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $ideecc = $this->session->userdata("eeccSession");

            if ($itemplan == null) {
                throw new Exception('ND');
            }
            if ($ideecc == null) {
                throw new Exception('Su sesion ha experido, ingrese nuevamente!!');
            }
            $arrayData = $this->M_editar_vr_po->getPtrByItemplan($itemplan, $ideecc);

            $cmbPtr .= "<option value=''>Seleccionar Ptr</option>";
            foreach ($arrayData as $row) {
                if ($row->ptr != null && $row->ptrEstacion != null) {
                    $data['empresacolab'] = $row->empresaColabDesc;
                    $data['jefatura'] = $row->jefatura;
                    $dataAlmCen = explode('|', $row->dataJefaturaEmp);
                    $data['codAlmacen'] = $dataAlmCen[0];
                    $data['codCentro'] = $dataAlmCen[1];
                    $data['idEmpresaColab'] = $dataAlmCen[3];
                    $data['idJefatura'] = $dataAlmCen[2];
                    $data['vr'] = $row->vr;
                    $data['estadoPlan'] = $row->estadoPlanDesc;
                    $cmbPtr .= "<option data-id_estacion='" . $row->idEstacion . "' data-id_subproyecto='" . $row->idSubProyecto . "' data-origen='" . $row->flg_origen . "' value='" . $row->ptr . "_" . $row->est_innova . "'>$row->ptrEstacion</option>";
                }
            }
            $data['error'] = EXIT_SUCCESS;
            $data['cmbPtr'] = $cmbPtr;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getVr()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $ptr_estado = $this->input->post('ptr');
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');

            $ptr = explode('_', $ptr_estado);
            if ($ptr == null || $ptr == '') {
                throw new Exception('ptr no registrado');
            }

            if ($itemplan == null || $itemplan == '') {
                throw new Exception('itemplan null');
            }

            if ($idEstacion == null || $idEstacion == '') {
                throw new Exception('idEstacion null');
            }
            $vr = $this->m_utils->getVrByPtr($ptr[0]);

            if ($vr != null) {
                $data['error'] = EXIT_SUCCESS;
            }

            $data['vr'] = $vr;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateVRPO()
    {

        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;

        try {

            $itemplan = $this->input->post('itemplan');
            $ptr_estado = $this->input->post('ptr');
            $idEstacion = $this->input->post('idEstacion');
            $vr = $this->input->post('vr');
            $flg_origen = $this->input->post('flg_origen');
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            $ptr = explode('_', $ptr_estado);

            if ($ptr == null || $ptr == '') {
                throw new Exception('ptr no registrado');
            }

            if ($itemplan == null || $itemplan == '') {
                throw new Exception('itemplan null');
            }

            if ($idEstacion == null || $idEstacion == '') {
                throw new Exception('idEstacion null');
            }

            if ($vr == null || $vr == '') {
                throw new Exception('vr null');
            }

            if ($flg_origen == null) {
                throw new Exception('Hubo un error al traer la PO');
            }

            $arrayUpdate = array(
                "vale_reserva" => $vr,
            );

            if ($flg_origen == 1) {
                $data = $this->M_editar_vr_po->updateVRByPTR($ptr[0], $arrayUpdate);
            } else if ($flg_origen == 2) {
                $data = $this->m_detalle_obra->updatePO($itemplan, $ptr[0], $idEstacion, $arrayUpdate);
            }

            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLog = array(
                    "codigo_po_ptr" => $ptr[0],
                    "origen" => $flg_origen,
                    "desc_actividad" => 'update',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual()
                );
                $data = $this->M_editar_vr_po->insertarLogEditVRPOPTR($arrayInsertLog);

                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                }
            }else{
                throw new Exception('Hubo un error al actualizar la PO');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
