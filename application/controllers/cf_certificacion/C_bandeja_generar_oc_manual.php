<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_generar_oc_manual extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_certificacion/m_bandeja_generar_oc_manual');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('excel');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                $data['cmbSubProyecto'] = __buildSubProyecto(null, 1, 1);
                $data['cmbEstadoFirma'] = __buildEstadoFirmaAll(1);

                $idRol = $this->session->userdata('idRol');
                $idEmpresaColab = $this->session->userdata('eeccSession');
                $perfil  = $this->session->userdata('descPerfilSession');

                $data['notificaciones'] = _getNotificacionHtml($idRol, $idEmpresaColab, $perfil);

                $data['listaEECC']     = $this->m_utils->getAllEECC();
                $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
                $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
                $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
                $data['listafase']     = $this->m_utils->getAllFase();
                
                $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
                $user_perfiles = $this->m_utils->getAllPerfilesByUserArray($idUsuario);
                $search_perfil_sp = array_search('81',$user_perfiles);// Buscar el perfil 81
                $is_bandeja_perms = $search_perfil_sp != '' ? true : false;

                
                $data['tablaPdtActa']     = $this->getTablaPdtActa(null,null,null,null, SISTEMA_WEB_PO);
                  

                $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
                $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
                $data['tituloSistema'] = 'GENERAR SOLICITUD DE EDICIÓN';
				
               $permisos =  $this->session->userdata('permisosArbol');
               $result = $this->lib_utils->getHTMLPermisosHublean($permisos, 308, 256, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
               //echo 'cert';
        	//    if($result['hasPermiso'] == true){
        	         $this->load->view('vf_certificacion/v_bandeja_generar_oc_manual',$data);
        	//    }else{
        	//        redirect('login','refresh');
        	//    }
		}else{
        	 redirect('login','refresh');
	    }
             
    }
	
	function getTablaPdtActa() {
		$arrayData = $this->m_bandeja_generar_oc_manual->getDataBandeja();

        $html = '<table id="tbPdtActa" class="table table-bordered table-striped table-striped w-100">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Itemplan</th>
							<th>SubProyecto</th>
							<th>EmpresaColab</th>
							<th>Estado Plan</th>
							<th>Fecha Creación</th>
                        </tr>
                    </thead>                    
                    <tbody>';
             			
                foreach($arrayData as $row){
                   
                    $html .=' <tr>
                            <th style="width:2%">
								<a data-itemplan="'.$row['itemplan'].'" data-pep1="'.$row['pep1'].'" 
									onclick="generarSolicitudOcManual($(this))"><i title="Validar" style="color:#A4A4A4;cursor:pointer" class="fal fa-check"></i>
								</a>                 
                            </th>
                            <td style="width:10%">'.$row['itemplan'].'</td>
							<td style="width:10%">'.$row['subProyectoDesc'].'</td>
							<td style="width:10%">'.$row['empresaColabDesc'].'</td>
							<td style="width:10%">'.$row['estadoPlanDesc'].'</td>
							<td style="width:10%">'.$row['fecha_creacion'].'</td>
                        </tr>';
                    }
             
            $html .='</tbody>
                        </table>';
                    
                
        return $html;
    }
	
	function generarSolicitudOcManual() {
		$data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $this->db->trans_begin();
            $fechaActual = $this->m_utils->fechaActual();
            
            $itemplan = $this->input->post('itemplan');
            $pep1     = $this->input->post('pep1');

            if($idUsuario == null || $idUsuario == '') {
                throw new Exception('se cerro la sesión cargue la pag.');
            }

            if($itemplan == null || $itemplan == '') {
                throw new Exception('no se encontro itemplan, comunicarse con el programador a cargo.');
            }
			
			if($pep1 == null || $pep1 == '') {
                throw new Exception('no se encontro itemplan, comunicarse con el programador a cargo.');
            }

            $flg = $this->m_utils->fn_create_solicitud_certi_oc($itemplan, 'PLAN', 2, null, $pep1, 265);
			
			if($flg == 13) {
				throw new Exception('tiene solicitud oc pendiente, verificar.');
			}
			
			if($flg != 1) {
				throw new Exception('no se genero la solicitud, verificar');
			}
			
            $data = $this->m_bandeja_pdt_acta->insertLogValidaFirmaInventario($this->inventario_db, $dataLog);

            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }

            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
	}
	
}
?>