<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_solicitud_usuario extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_usuario_siom/m_bandeja_solicitud_usuario');
        $this->load->model('mf_utils/m_utils');

        $this->load->library('lib_utils');
        $this->load->helper('url');

    }

    
    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        $idUsuario = $this->session->userdata('idPersonaSession');

        if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 144, 217);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_WORKFLOW, 217, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            
            $data['cmbContratos']    = __buildCmbContratosAll();
            $data['cmbEmpresaColab'] = __buildCmbEmpresaColab();
            $data['cmbZona']         = __buildCmbZona();
            $data['cmbPerfil']       = __buildCmbPerfil();
            $data['tabla']           = $this->getTablaBandeja(null, null, null);
            $this->load->view('vf_usuario_siom/v_bandeja_solicitud_usuario', $data);
        } else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }

    function getTablaBandeja($dni, $idEmpresaColab, $estado) {
        $data = $this->m_bandeja_solicitud_usuario->getBandejaSolicitudUsuario($dni, $idEmpresaColab, $estado);


        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
							<th>C&Oacute;DIGO</th>
                            <th>DNI</th>
                            <th>NOMBRE</th>
                            <th>IMEI</th>
                            <th>PEFIL</th>
                            <th>ZONA</th>
                            <th>TIPO SOLICITUD</th>
                            <th>ESTADO</th>
                            <th>EMAIL</th>
                            <th>TEL&Eacute;FONO</th>
                            <th>FECHA REGISTRO</th>
                            <th>FECHA ATENCI&Oacute;N</th>
                            <th>EECC</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $btnRechazar = null;
                    $btnAprobar  = null;

                    if($row['estado'] == 'PENDIENTE') { //PENDIENTE
						if($row['flg_tipo_solicitud'] == 1) {
							$funcion = 'openModalUsuario($(this))';
						} else {
							$funcion = 'aprobCanSolicitud($(this))';
						}
						
                        $btnRechazar  = '<i style="color:#A4A4A4;cursor:pointer" data-estado="3" data-id_solicitud="'.$row['idSolicitudUsuario'].'" data-flg_tipo_solicitud="'.$row['flg_tipo_solicitud'].'"
                                            class="zmdi zmdi-hc-2x zmdi-block-alt" title="Rechazar Solicitud" onclick="openModalRechazar($(this))"></i>';
                        $btnAprobar = '<a><i style="color:#A4A4A4;cursor:pointer" data-estado="2" data-id_solicitud="'.$row['idSolicitudUsuario'].'" data-flg_tipo_solicitud="'.$row['flg_tipo_solicitud'].'"
                                            class="zmdi zmdi-hc-2x zmdi-check-circle" onclick="'.$funcion.'" title="Aprobar Solicitud"></i></a>';
                    } 
                   

                    $html .=' <tr>
                                <td>'.$btnAprobar.' '.$btnRechazar.'</td>
								<td>'.utf8_decode($row['codigo']).'</td>
                                <td>'.utf8_decode($row['dni']).'</td>
                                <td>'.utf8_decode($row['nombre']).'</td>
                                <td>'.utf8_decode($row['imei']).'</td>
                                <td>'.utf8_decode($row['arrayPerfilDesc']).'</td>
                                <td>'.utf8_decode($row['arrayZonaDesc']).'</td>
                                <td>'.utf8_decode($row['flg_tipo_estado']).'</td>
                                <td>'.utf8_decode($row['estado']).'</td>
                                <td>'.utf8_decode($row['email']).'</td>
                                <td>'.utf8_decode($row['telefono']).'</td>
                                <td>'.$row['fecha_registro'].'</td>
                                <td>'.$row['fecha_aprob'].'</td>
                                <td>'.utf8_decode($row['empresaColabDesc']).'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function aprobCanSolicitud() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $idSolicitud      = $this->input->post('idSolicitud');
            $estado           = $this->input->post('estado');  
            $flgTipoSolicitud = $this->input->post('flg_tipo_solicitud');
            $observRechazo    = $this->input->post('observRechazo');
            
            $this->db->trans_begin();
            if($idSolicitud == null) {
                throw new Exception('estado solicitud nulo, comunicarse con el programador.');
            }

            if($estado == null) {
                throw new Exception('estado nulo, comunicarse con el programador.');
            }

            if($flgTipoSolicitud == null) {
                throw new Exception('estado tipo solicitud nulo, comunicarse con el programador.');
            }

            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null) {
                throw new Exception('Su sesi&oacute;n a caducado, recargue nuevamente la pagina');
            }
            $fechaActual = $this->m_utils->fechaActual();

            if($estado == 2) {//APROBADO
                if($flgTipoSolicitud == 1) { //SOLICITUD PARA CREACION DE USUARIO
                    $data = $this->m_bandeja_solicitud_usuario->insertDataUsuario($idSolicitud, $idUsuario, $fechaActual);
                } else if($flgTipoSolicitud == 2) { //SOLICITUD PARA MODIFICACION DE USUARIO
                    $data = $this->m_bandeja_solicitud_usuario->modificacionUsuario($idSolicitud);
                } else if($flgTipoSolicitud == 3) { //SOLICITUD PARA BAJA DE USUARIO
                    $data = $this->m_bandeja_solicitud_usuario->bajaUsuario($idSolicitud);
                }

                if($data['error'] == EXIT_ERROR) {
                    $this->db->trans_rollback();
                    //throw new Exception('esta solicitud tiene errores, no se inserto.');
                } else {
                    $arrayUpdate = array(
                                            'estado'           => $estado,
                                            'fecha_aprob'      => $fechaActual,
                                            'id_usuario_aprob' => $idUsuario
                                        );
                    $data = $this->m_bandeja_solicitud_usuario->updateSolicitud($idSolicitud, $arrayUpdate);
                    if($data['error'] == EXIT_ERROR) {
                        $this->db->trans_rollback();
                        throw new Exception('esta solicitud tiene errores, no se actualizo.');
                    } else {
                        $this->db->trans_commit();
                    }
                }
            } else if($estado == 3) {
                $arrayUpdate = array(
                                        'estado'              => $estado,
                                        'fecha_aprob'         => $this->m_utils->fechaActual(),
                                        'id_usuario_aprob'    => $idUsuario,
                                        'observacion_rechazo' => $observRechazo
                                    );
                $data = $this->m_bandeja_solicitud_usuario->updateSolicitud($idSolicitud, $arrayUpdate);

                if($data['error'] == EXIT_ERROR) {
                    $this->db->trans_rollback();
                    throw new Exception('esta solicitud tiene errores, no se actualizo.');
                } else {
                    $this->db->trans_commit();
                }
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
    }
	
	function ingresarUsuario() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $idSolicitud = $this->input->post('idSolicitud');
            $estado      = $this->input->post('estado');  
            $flgTipoSolicitud = $this->input->post('flg_tipo_solicitud');
			$usuario     = $this->input->post('usuario');
			$clave       = $this->input->post('clave');

            $this->db->trans_begin();
            if($idSolicitud == null) {
                throw new Exception('estado solicitud nulo, comunicarse con el programador.');
            }

            if($estado == null) {
                throw new Exception('estado nulo, comunicarse con el programador.');
            }

            if($flgTipoSolicitud == null) {
                throw new Exception('estado tipo solicitud nulo, comunicarse con el programador.');
            }

            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null) {
                throw new Exception('Su sesi&oacute;n a caducado, recargue nuevamente la pagina');
            }
            $fechaActual = $this->m_utils->fechaActual();
			
			$countUsuarioReplic = $this->m_bandeja_solicitud_usuario->countUsuarioByusuario($usuario);
			
			if($countUsuarioReplic > 0) {
				throw new Exception('Ya existe este usuario');
			}
			
            if($estado == 2) {//APROBADO
                $data = $this->m_bandeja_solicitud_usuario->insertDataUsuario($idSolicitud, $idUsuario, $fechaActual, $usuario, $clave);

                if($data['error'] == EXIT_ERROR) {
                    $this->db->trans_rollback();
                    //throw new Exception('esta solicitud tiene errores, no se inserto.');
                } else {
                    $arrayUpdate = array(
                                            'estado'           => $estado,
                                            'fecha_aprob'      => $fechaActual,
                                            'id_usuario_aprob' => $idUsuario,
											'usuario'          => $usuario,
											'clave'            => $clave
                                        );
                    $data = $this->m_bandeja_solicitud_usuario->updateSolicitud($idSolicitud, $arrayUpdate);
                    if($data['error'] == EXIT_ERROR) {
                        $this->db->trans_rollback();
                        throw new Exception('esta solicitud tiene errores, no se actualizo.');
                    } else {
                        $this->db->trans_commit();
                    }
                }
            } else if($estado == 3) {
                $arrayUpdate = array(
                                        'estado'           => $estado,
                                        'fecha_aprob'      => $this->m_utils->fechaActual(),
                                        'id_usuario_aprob' => $idUsuario
                                    );
                $data = $this->m_bandeja_solicitud_usuario->updateSolicitud($idSolicitud, $arrayUpdate);

                if($data['error'] == EXIT_ERROR) {
                    $this->db->trans_rollback();
                    throw new Exception('esta solicitud tiene errores, no se actualizo.');
                } else {
                    $this->db->trans_commit();
                }
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
	}

    function filtrarTablaSolicitudUsuario() {
        $estado         = $this->input->post('estado');
        $dni            = $this->input->post('dni');
        $idEmpresaColab = $this->input->post('idEmpresaColab');

        $estado         = ($estado       == '') ? NULL : $estado;
        $dni            = ($dni == '') ? NULL : $dni;
        $idEmpresaColab = ($idEmpresaColab     == '') ? NULL : $idEmpresaColab;

        $data['tabla'] = $this->getTablaBandeja($dni, $idEmpresaColab, $estado);
        echo json_encode(array_map('utf8_encode', $data)); 
    }
}