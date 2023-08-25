<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_usuario extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_usuario');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $pepsub = 0;


            $data['listartabla'] = $this->makeHTLMTUsuario($this->m_usuario->getUsuario());

            /* $data['listaperfiles'] = $this->m_utils->getAllPerfiles();*/
            $data['listaperfiles'] = $this->m_utils->getAllPerfilessinAdmin();
            $data['listaeecc'] = $this->m_utils->getAllEECCINCLTDP();
            $data['listazonas'] = $this->m_utils->getAllZonal();
            $data['modaleditar'] = $this->makeHTLMTModalEditar();
            //$data['opcionesPerfil'] = $this->m_utils->getAllPerfiles();

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['listaProy']     = $this->m_utils->getProyectoCmb();
            $data['listaJefatura'] = $this->m_utils->getJefaturaTB();
            $data['listaRestricciones'] = $this->m_utils->getRestriccionesAll(NULL, 1);
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_BANDEJA_PRE_APROB);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_MANTENIMIENTO_USUARIO, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            $data['prueba'] = 'cristobal estudia';
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_usuario', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }


    public function makeHTLMTUsuario($listartabla)
    {

        $html = '
        <div>
            <a href="mNuevoUsuario" class="btn btn-primary" >AGREGAR USUARIO</a>
        </div>
        
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Usuario</th>                            
                            <th>Nombres</th>
                            <th>Ape. Paterno</th>
                            <th>Ape. Materno</th>
                            <th>DNI</th>
                            <th>Estado Usuario</th>
                            <th>Emp. Colab</th>
                            <th>Perfil</th>
                            <th>Firma</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($listartabla->result() as $row) {

            $perfiles = $this->getEmpresa($row->perfil);

            $firma = !empty($row->firma) ? '<a donwload title="Descargar Evidencia" href="public/img/' . $row->firma . '" target="_blank">
            <i class="zmdi zmdi-hc-2x zmdi-download"></i>
        </a>' : '';

            $html .= ' <tr>
							<td>' . $row->usuario . '</td> 
                            <td>' . $row->nombres . '</td> 
                            <td>' . $row->ape_paterno . '</td>
                            <td>' . $row->ape_materno . '</td>
                            <td>' . $row->dni . '</td>
                            <td>' . ($row->estado == 1 ? 'ACTIVO' : 'INACTIVO') . '</td>
                            <td>' . ($row->eecc != '' ? $row->eecc : 'NINGUNO') . '</td>
                            <td>' . $perfiles . '</td>
                            <td>' . $firma . '</td>
                            
                            <td style="text-align:center">
                                <a style="cursor:pointer; color: var(--verde_telefonica)" data-toggle="modal" data-id="' . $row->id_usuario . '" data-target="#modal-large" onclick="editar(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
                                                
                            ' . ($row->estado == 1 ? '<a <a style="cursor:pointer; color: red" data-id ="' . $row->id_usuario . '" data-estado="" data-toggle="modal" data-target="#eliminar" onclick="desactivar(this)"><i class="zmdi zmdi-hc-2x zmdi-block"></i></a>' : '<a style="cursor:pointer; color: var(--verde_telefonica)" data-toggle="modal" data-id="' . $row->id_usuario . '" data-toggle="modal" data-target="#eliminar" onclick="activar(this)"><i class="zmdi zmdi-hc-2x zmdi-turning-sign"></i></a>') . '</td>
						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }



    public function getusuario()
    {

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $nombres = $this->input->post('nombres');
            $paterno = $this->input->post('paterno');
            $materno = $this->input->post('materno');
            $dni = $this->input->post('dni');
            $email = $this->input->post('email');
            $pass = $this->input->post('pass');
            $this->m_usuario->insertUsuario();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }



    public function makeHTLMTModalEditar()
    {

        $html = '
        <div class="modal" id="editarusuario" tabindex="-1" role="dialog" aria-labellebdy="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Usuario</h4> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>  
                    </div>
                    <div class="modal-body">
                       <form action="" method="POST">              		
                       		<div class="form-group">
                       			<label for="proyecto">Proyecto:</label>
                       			<input type="text" class="form-control" name="pep1Edit" id="pep1Edit">
                       		</div>
                       		<div class="form-group"s>
                       			<label for="tcentral">Tipo Central:</label>
                       			<input type="text" class="form-control" name="pep2" id="pep2Edit">
                       		</div>
                            <div class="form-group"s>
                       			<label for="tlabel">Tipo Label:</label>
                       			<input type="text" class="form-control" name="pep2" id="pep2Edit">
                       		</div>
                            <button type="submit" class="btn btn-success waves-effect" id="refresh">Modificar</button>
                       </form>
                    </div>
                </div>
            </div>
        </div> ';


        return utf8_decode($html);
    }

    public function makeHTLMTModalEliminar()
    {

        $html = '
        <div class="modal" id="eliminar" tabindex="-1" role="dialog" aria-labellebdy="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Desactivar Usuario</h4> 
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>       
                    </div>
                    <div class="modal-body">              		
                        <div class="form-group">
                            <p>La Pep seleccionada sera desactivada</p>
                        </div>
                        <button type="submit" class="btn btn-danger waves-effect" id="refresh">Desactivar</button>
                    </div>
                </div>
            </div>
        </div> ';


        return utf8_decode($html);
    }

    //DESACTIVAR USUARIO
    public function updatedesac()
    {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('id');
            $data = $this->m_usuario->updateUsuEstadoD($id);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    //ACTIVAR USUARIO
    public function updateactiv()
    {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('id');
            $data = $this->m_usuario->updateUsuEstadoA($id);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInforUsuarioById()
    {

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('id');
            $usuario = $this->m_usuario->getUsuarioById($id);
            $data['nombres'] = $usuario['nombres'];
            $data['apePaterno'] = $usuario['ape_paterno'];
            $data['apeMaterno'] = $usuario['ape_materno'];
            $data['dni'] = $usuario['dni'];
            $data['email'] = $usuario['email'];
            $data['perfil'] = $usuario['id_perfil'];
            $data['empresa'] = $usuario['id_eecc'];
            $data['zonas'] = $usuario['zonas'];
            $data['usuario'] = $usuario['usuario'];
            $data['firma'] = $usuario['firma'];
            /*$data['pass'] = $usuario['pass'];*/

            $objValidaCerti = $this->m_usuario->getDataValidaCerti($id);
            $objRestriccion = $this->m_usuario->getUsuarioRestriccionByUsuario($id, 1);

            $data['arrayJefatura']   = $objValidaCerti['arrayJefatura'];
            $data['arrayNivelValidacion'] = $objValidaCerti['arrayNivelValidacion'];
            $data['arrayIdProyecto']      = $objValidaCerti['idProyecto'];

            $data['restricciones']   = $objRestriccion['id_tipo_restriccion'];
            /**miguel rios 11062018**/
            $data['accesoSINF'] = $usuario['idUsuarioSinfix'];
            /****/
            $this->session->set_flashdata('idUsuario', $id);
            $data['error']    = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateUser()
    {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {

            $id = $this->session->flashdata('idUsuario');
            $nombres = $this->input->post('nombres');
            $dni = $this->input->post('dni');
            $apePaterno = $this->input->post('paterno');
            $apeMaterno = $this->input->post('materno');
            $email = $this->input->post('email');
            $empresa = $this->input->post('empresa');
            $perfil = $this->input->post('perfil');
            $zonas = $this->input->post('zonas');
            $pass = $this->input->post('pass');
            $user = $this->input->post('user');

            $niveles    = $this->input->post('cmbNivel');
            $idJefatura = $this->input->post('cmbJefatura');
            $idProyecto = $this->input->post('cmbProyectoEdit');

            $restricciones = $this->input->post('cmbRestriccion');
            /*****miguel rios 11062018************/
            $accesoSINFIX = $this->input->post('accesoSinFix');

            if ($accesoSINFIX == "on") {
                $accesoSINFIX = "1";
            } else {
                $accesoSINFIX = null;
            }

            $arrayNiveles  = $niveles;
            $arrayProyecto = $idProyecto;
            $arrayJefatura = $idJefatura;
            $arrayRestricciones = $restricciones;
            $arrayInsertValidCerti = array();
            if ($idJefatura != null) {
                if ($idProyecto == null || $id == null || count($arrayNiveles) == 0) {
                    throw new Exception('Debe ingresar los datos completos de validacion certificacion.');
                }
            }

            if ($idProyecto != null) {
                if ($idJefatura == null || $id == null || count($arrayNiveles) == 0) {
                    throw new Exception('Debe ingresar los datos completos de validacion certificacion.');
                }
            }

            if ($arrayNiveles[0] != null) {

                if (count($arrayNiveles) > 0) {
                    if ($idJefatura == null || $id == null) {
                        throw new Exception('Debe ingresar los datos completos de validacion certificacion.');
                    }
                }
                foreach ($arrayNiveles as $row) {
                    if (count($arrayProyecto) > count($arrayJefatura)) {
                        foreach ($arrayJefatura as $rowJ) {
                            foreach ($arrayProyecto as $rowP) {
                                $arrayInsertValidCerti[] = array(
                                    "idJefatura"       => $rowJ,
                                    "nivel_validacion" => $row,
                                    "idProyecto"       => $rowP,
                                    "idUsuario"        => $id
                                );
                            }
                        }
                    } else {
                        foreach ($arrayProyecto as $rowP) {
                            foreach ($arrayJefatura as $rowJ) {
                                $arrayInsertValidCerti[] = array(
                                    "idJefatura"       => $rowJ,
                                    "nivel_validacion" => $row,
                                    "idProyecto"       => $rowP,
                                    "idUsuario"        => $id
                                );
                            }
                        }
                    }
                }
                $this->m_usuario->updatePermisoNivelesValid($arrayInsertValidCerti);
            }

            if ($arrayRestricciones[0] != null) {
                foreach ($arrayRestricciones as $rowR) {
                    $arrayDataRestricciones[] = array(
                        "idUsuario"           => $id,
                        "id_tipo_restriccion" => $rowR
                    );
                }
                $this->m_usuario->updateRestricciones($arrayDataRestricciones);
            }

            // Firma digital
            $rutaFirma = 'public/img';
            $firma = null;
            if (isset($_FILES['fileFirma'])) {
                $filename = 'firma_' . $user . '.png';
                $upload = $rutaFirma . '/' . $filename;

                if (move_uploaded_file($_FILES['fileFirma']['tmp_name'], $upload)) {
                    $firma = $filename;
                    chmod($upload, 0777);
                } else {
                    throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
                }
            } else {
                $firma = $this->input->post('fileFirma');
            }

            $data = $this->m_usuario->updateUsuario(
                $id,
                $nombres,
                $dni,
                $apePaterno,
                $apeMaterno,
                $email,
                $empresa,
                $perfil,
                $zonas,
                $user,
                $pass,
                $accesoSINFIX,
                $firma
            );

            /**********************************/
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        // redirect('mUsuario', 'refresh');
        echo json_encode($data);
    }

    /********************10082018**************************/
    public function getEmpresa($listado)
    {
        $arr2 = explode(",", $listado);

        $dato = 1;
        $empresas = "";

        foreach ($arr2 as $valor) {
            if (trim($valor) != "") {
                if ($dato == 1) {
                    $empresas = $this->m_utils->getPerfiles($valor);
                    $dato++;
                } else {
                    $empresas .= "<br>" . $this->m_utils->getPerfiles($valor);
                }
            }
        }
        return $empresas;
    }

    /****************************************************/
}
