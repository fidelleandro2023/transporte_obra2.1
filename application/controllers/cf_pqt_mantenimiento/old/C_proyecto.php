<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * @author Gustavo Sedano
 * 08/08/2019
 *
 */
class C_proyecto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_proyecto');
        $this->load->model('mf_pqt_mantenimiento/m_pqt_proyecto');
        $this->load->model('mf_pqt_mantenimiento/m_subproyecto_fases_cant_itemplan');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
			 
            $data['listaTipoCentral'] = $this->m_utils->getAllTipoCentral();
            $data['listaTipoLabel'] = $this->m_utils->getAllTipoLabel();
            $data['listaEstaciones'] = $this->m_utils->getAllIdEstacionesPaquetizado();
            $data['listaProyectos'] = $this->m_utils->getAllProyecto();
            $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
            $data['listaSubProyecto'] = $this->m_proyecto->getAllSubProyectoDesc();
            $data['listaActividad'] = $this->m_proyecto->getAllActividades();
          
            //MODULO PAQUETIZADO
            $data['listaPqtTipoFactorMedicion'] = $this->m_utils->getAllPqtTipoFactorMedicion();
            $data['tbSubproyecto'] 	= $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoPaquetizadoDesc());
            $data['tbProyecto'] 	= $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());
            $data['nombreUsuario'] 	= $this->session->userdata('usernameSession');
            $data['perfilUsuario'] 	= $this->session->userdata('descPerfilSession');
            $permisos 				= $this->session->userdata('permisosArbol');
            $result 				= $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] 		= $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_pqt_mantenimiento/v_proyecto', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }

    }

    public function makeHTLMTablaSubPro($lista)
    {

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>PROYECTO</th>
                            <th>SUB PROYECTO</th>
                            <th>TIPO PLANTA</th>
                            <th>TIEMPO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($lista->result() as $row) {

            $html .= ' <tr>

							<td>' . $row->proyectoDesc . '</td>
							<td>' . utf8_decode($row->subProyectoDesc) . '</td>
							<td>' . $row->tipoPlantadesc . '</td>
							<td>' . $row->tiempo . '</td>
						    <th><a data-id_spro="' . $row->idSubProyecto . '" onclick="editSubProyecto(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></th>

						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function makeHTLMTablaActividadSubPro($lista)
    {

        $html = '<table id="data-table7" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>SUB PROYECTO</th>
                            <th>ACTIVIDAD</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        if (!is_null($lista)) {
            foreach ($lista->result() as $row) {

                $html .= ' <tr>

							<td>' . utf8_decode($row->subProyectoDesc) . '</td>
							<td>' . $row->actividad . '</td>
						    <th><a data-id_sproact="' . $row->idactividad_x_subProyecto . '" onclick="editSubProyectoActividad(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></th>

						</tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $SubProy = $this->input->post('subProy');
            $data['tablaSubProyPep'] = $this->makeHTLMTablaPepSubPro($this->m_proyecto->getPep1SubProy($SubProy));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaProyecto($lista)
    {
        $html = '<table id="data-table4" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>

                            <th>PROYECTO</th>
                            <th>TIPO CENTRAL</th>
                             <th>TIPO LABEL</th>
            <th></th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($lista->result() as $row) {

            $html .= ' <tr>

							<td>' . $row->proyectoDesc . '</td>
							<td>' . $row->tipoCentralDesc . '</td>
						    <td>' . $row->tipoLabelDesc . '</td>
						    <th>
						        <a data-id_pro="' . $row->idProyecto . '" onclick="editProyecto(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>
						        <a data-id_pro="' . $row->idProyecto . '" onclick="getInforSubProyectoPorProyecto(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/lupalog.png"></a>
						    </th>
						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function addProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $decripcion = $this->input->post('inputDescPro');
            $tipoCentral = $this->input->post('selectTipoCentral');
            $tipoLabel = $this->input->post('selectTipoLabel');

            $data = $this->m_proyecto->insertProyecto($decripcion, $tipoCentral, $tipoLabel);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            //////////////////////////////28092018///////////////////////
            $data2 = $this->m_proyecto->insertLogProyecto('nuevo', '', $decripcion, $this->session->userdata('usernameSession'));
            if ($data2['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoProyecto()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('idProyecto');
            $proyecto = $this->m_proyecto->getProyectoInfo($id);

            $data['proyecto'] = $proyecto['proyectoDesc'];
            $data['central'] = $proyecto['idTipoCentral'];
            $data['label'] = $proyecto['idTipoLabel'];
            //$data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $decripcion = $this->input->post('inputDescPro2');
            $idTipocentral = $this->input->post('selectTipoCentral2');
            $tipoLabel = $this->input->post('selectTipoLabel2');
            $id = $this->input->post('id');
            $data = $this->m_proyecto->editarProyecto($id, $idTipocentral, $tipoLabel, $decripcion);
            //log_message('error', $id . '-' . $idTipocentral . '-');
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar createCentral');
            }

            ////////////////////////////28092018////////////////////////
            $data2 = $this->m_proyecto->insertLogProyecto('update', $id, $decripcion, $this->session->userdata('usernameSession'));
            if ($data2['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function existeSubPepArea()
    {
        $subpro = $this->input->post('subpro');
        $area = $this->input->post('area');
        $pep = $this->input->post('pep');
        $cant = null;
        if ($subpro != null && $area != null && $pep != null) {
            $res = $this->m_proyecto->existPepSubArea($subpro, $area, $pep);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        } else {
            $cant = '1'; //Si hay un error que simule que si existe
        }
        echo $cant;
    }

    public function addSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idProyecto = $this->input->post('selectProyecto2');
            $subProDesc = $this->input->post('inputDescSubPro');
            $tiempo = $this->input->post('selectTiempo');
            $planta = $this->input->post('selectTipoPlanta');
			$tFases = json_decode($this->input->post('tFases'));           
            $flgCheckOpex    = $this->input->post('flgCheckOpex') ? $this->input->post('flgCheckOpex') : null;
			$flgCheckOpex = ($flgCheckOpex == 1? FLG_SUB_OPEX : FLG_SUB_NO_OPEX);			
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;          
            
            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }
            
            $flgExisteSubProy = $this->m_utils->countSubProyByDesc(strtoupper($subProDesc));
            if ($flgExisteSubProy > 0) {
                throw new Exception('Ya existe un subproyecto con ese nombre, ingrese otro!');
            }			
			
		    $dataInsert = array( "subProyectoDesc"  		  => strtoupper($subProDesc),
								"tiempo" 				  => $tiempo,
								"idProyecto" 			  => $idProyecto,
								"idTipoPlanta" 			  => $planta,
								"idPqtTipoFactorMedicion" => 1,//1 = OBRA, POR DEFECTO
								"estado" 				  => 1,
								"flg_opex" 				  => $flgCheckOpex);
			
			$estacionesArea = $this->m_proyecto->getAllestacionAreaByTipoPlanta($planta); 			
            $data = $this->m_proyecto->createNewSubProyectoDespliegue($dataInsert, $estacionesArea, $idUsuario, $tFases);          
            $data['tbSubproyecto'] = $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoPaquetizadoDesc());            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoSubProyecto()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('idSubProyecto');
            $subProyecto = $this->m_pqt_proyecto->getSubProyectoInfoPqt($id);

            $data['descripcion'] = utf8_decode($subProyecto['subProyectoDesc']);
            $data['tiempo'] = $subProyecto['tiempo'];
            $data['proyecto'] = $subProyecto['idProyecto'];
            $data['tipoPlanta'] = $subProyecto['idTipoPlanta'];
            $data['areas'] = $subProyecto['estaciones'];
			$data['flg_opex'] = $subProyecto['flg_opex'];
            $data['cmbTipoComplejidad'] = $this->makeHTMLTipoComplejidad($this->m_utils->getAllTipoComplejidad(), $subProyecto['idTipoComplejidad']);
            $data['cmbTipoSubProyecto'] = $this->makeHTMLTipoSubProyecto($this->m_utils->getAllTipoSubProyecto(), $subProyecto['idTipoSubProyecto']);
            
            $data['tipoFactorMedicion'] = $subProyecto['idPqtTipoFactorMedicion'];
            $data['idAprobacionAutomatica'] = $subProyecto['aprobacionAutomatica_fg'];
            $data['idAdjudicacionAutomatica'] = $subProyecto['adjudicacionAutomatica_fg'];
            $data['r_paquetizado_fg'] = $subProyecto['paquetizado_fg'];
            
            $data['tFase'] = $this->makeHTMLTablaFases($this->m_subproyecto_fases_cant_itemplan->getFasesPorSubProyecto($id), $id);
            //$this->makeHTMLTablaFases($this->m_subproyecto_fases_cant_itemplan->getFasesPorSubProyecto($subProyecto['idTipoSubProyecto']));
            
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	public function updateSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $estacionarea               = $this->input->post('estaciones');
            $idTipoPlanta               = $this->input->post('selectTipoPlanta2');
            $tiempo                     = $this->input->post('selectTiempo2');
            $descripcion                = $this->input->post('inputDescSubPro2');
            $idProyecto                 = $this->input->post('selectProyecto3');
            $oldSubPro                  = $this->input->post('oldSubPro');
            $idSubProyecto              = $this->input->post('id');
            $idComplejidad              = $this->input->post('idComplejidad') ? $this->input->post('idComplejidad') : null;
            $idTipoSubProyecto          = $this->input->post('idTipoSubProyecto') ? $this->input->post('idTipoSubProyecto') : null;
            $idTipoFactorMedicion       = $this->input->post('idTipoFactorMedicion') ? $this->input->post('idTipoFactorMedicion') : null;
            $idAprobacionAutomatica     = $this->input->post('idAprobacionAutomatica') ? $this->input->post('idAprobacionAutomatica') : null;
            $idAdjudicacionAutomatica   = $this->input->post('idAdjudicacionAutomatica') ? $this->input->post('idAdjudicacionAutomatica') : null;
            $chbxFichaTecnica           = $this->input->post('checkFichaTec') ? $this->input->post('checkFichaTec') : null;
            $idUsuario                  = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
			$flgCheckOpex    = $this->input->post('flgCheckOpex') ? $this->input->post('flgCheckOpex') : null;
			
			$flgCheckOpex = ($flgCheckOpex == 1? FLG_SUB_OPEX : FLG_SUB_NO_OPEX);
			
            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }
            /**cambios czavalacas 26.11.2019**/
            /**realizamos una validacion de estaciones que ya cuenten con Po**/
            
            $estacionesArray = explode(',', $estacionarea);//estaciones de la interfaz            
            $estacionesPtr = $this->m_pqt_proyecto->getEstacionesPTRBySubProyecto($idSubProyecto);//estaciones Actuales
            //validamos cuales se quedan, cuales se van, y cuales se registraran.
            $estacionesInsert = array();
            $estacionesDelete = array();
            
            foreach($estacionesPtr as $row_1){//foreach para eliminar
                $delete = true;
                foreach($estacionesArray as $row_2){
                    if($row_1->idEstacion == $row_2){//se queda
                        $delete = false;
                        break;
                    }
                }
                if($delete){
                    if($row_1->total <= 0){
                        array_push($estacionesDelete, $row_1->idEstacion);
                    }else{
                        throw new Exception("No se puede eliminar la estacion '".utf8_decode($row_1->estacionDesc)."', Existen Obras con mas de 1 PO asociadas a esta Estacion.");
                    }
                }
            }            
            
            foreach($estacionesArray as $row_1){//foreach para insertar
                $insert = true;
                foreach($estacionesPtr as $row_2){
                    if($row_1 == $row_2->idEstacion){//se queda
                        $insert = false;
                        break;
                    }
                }
                if($insert){
                    array_push($estacionesInsert, $row_1);
                }
            }
            
            $subproyectoEstacionInsert = array();
            if(count($estacionesInsert) >   0){//si hay datos que insertar.
                $estacionesAreasInsert = $this->m_pqt_proyecto->getEstacionesAreasByEstaciones($estacionesInsert);
                foreach ($estacionesAreasInsert as $row){
                    $subProEsta = array(
                        'idSubProyecto' => $idSubProyecto,
                        'idEstacionArea' => $row->idEstacionArea
                    );
                    array_push($subproyectoEstacionInsert, $subProEsta);
                }
            }
            
            $subproyectoEstacionDelete = array();
            if(count($estacionesDelete) >   0){//si hay datos que insertar.
                $listSeDelete = $this->m_pqt_proyecto->getIdSubProyectoEstacion($estacionesDelete, $idSubProyecto);
                foreach ($listSeDelete as $row){                    
                    array_push($subproyectoEstacionDelete, $row->idSubProyectoEstacion);
                }
            }
           
            $subproyectoData = array(
                                    "subProyectoDesc"           => strtoupper($descripcion),
                                    "tiempo"                    => $tiempo,
                                    "idProyecto"                => $idProyecto,
                                    "idTipoPlanta"              => $idTipoPlanta,
                                    "idTipoComplejidad"         => $idComplejidad,
                                    "idTipoSubProyecto"         => $idTipoSubProyecto,
                                    "idPqtTipoFactorMedicion"   => $idTipoFactorMedicion,
                                    "aprobacionAutomatica_fg"   => $idAprobacionAutomatica,
                                    "adjudicacionAutomatica_fg" => $idAdjudicacionAutomatica,
									"flg_opex"                  => $flgCheckOpex
                                );
                    
            
            $logEditSubProyecto = array(
                                    "idSubProyecto"     => $idSubProyecto,
                                    "idUsuario"         => $idUsuario,
                                    "fecha_registro"    => date("Y-m-d H:i:s"),
                                    "actividad"         => 'update',
                                    "idTipoSubProyecto" => $idTipoSubProyecto
            );
            
            /*log_message('error', '$subproyectoEstacionInsert:'.print_r($subproyectoEstacionInsert, true));
            log_message('error', '$estacionesDelete:'.print_r($estacionesDelete, true));
            log_message('error', '$estacionesInsert:'.print_r($estacionesInsert, true));            
            log_message('error', '$subproyectoEstacionDelete:'.print_r($subproyectoEstacionDelete, true));*/
            $data = $this->m_pqt_proyecto->updateSubProyectoV2($idSubProyecto,$subproyectoData, $logEditSubProyecto, $subproyectoEstacionDelete, 
																$subproyectoEstacionInsert);

            if ($data['error'] == EXIT_SUCCESS) {
                $data['tbSubproyecto'] = $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoPaquetizadoDesc());
            }else{
                throw new Exception('Ocurrio un error, refresque la ventana y vuelva a intentarlo. De Persistir el error comuniquese con el Administrador.');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
/* comentado por czavalacas 26.11..2019
    public function updateSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $estacionarea = $this->input->post('estaciones');
            $idTipoPlanta = $this->input->post('selectTipoPlanta2');
            $tiempo = $this->input->post('selectTiempo2');
            $descripcion = $this->input->post('inputDescSubPro2');
            $idProyecto = $this->input->post('selectProyecto3');
            $oldSubPro = $this->input->post('oldSubPro');
            $id = $this->input->post('id');
            $idComplejidad = $this->input->post('idComplejidad') ? $this->input->post('idComplejidad') : null;
            $idTipoSubProyecto = $this->input->post('idTipoSubProyecto') ? $this->input->post('idTipoSubProyecto') : null;
            $idTipoFactorMedicion = $this->input->post('idTipoFactorMedicion') ? $this->input->post('idTipoFactorMedicion') : null;
            $idAprobacionAutomatica = $this->input->post('idAprobacionAutomatica') ? $this->input->post('idAprobacionAutomatica') : null;
            $idAdjudicacionAutomatica = $this->input->post('idAdjudicacionAutomatica') ? $this->input->post('idAdjudicacionAutomatica') : null;
            
            $chbxFichaTecnica = $this->input->post('checkFichaTec') ? $this->input->post('checkFichaTec') : null;
            
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            $data = $this->m_proyecto->editarSubProyectoPaquetizado($id, $idProyecto, $descripcion, $tiempo, 
                $idTipoPlanta, $estacionarea, $oldSubPro, $idComplejidad, 
                $idTipoSubProyecto, $idTipoFactorMedicion, $idAprobacionAutomatica,
                $idAdjudicacionAutomatica);

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar createCentral');
            } else {
                $arrayInsertLogSubProy = array(
                    "idSubProyecto" => $id,
                    "idUsuario" => $idUsuario,
                    "fecha_registro" => date("Y-m-d H:i:s"),
                    "actividad" => 'update',
                    "idTipoSubProyecto" => $idTipoSubProyecto
                );
                $data = $this->m_proyecto->insertarLogSubProy($arrayInsertLogSubProy);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbSubproyecto'] = $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoPaquetizadoDesc());
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
*/
    public function getSubproActividades()
    {
        $idSubproyecto = $this->input->post('idSubProyecto');

        $data['tbSubProyectoActividad'] = $this->makeHTLMTablaActividadSubPro($this->m_proyecto->getAllSubproyectoActividad($idSubproyecto));

        echo json_encode(array_map('utf8_encode', $data));
    }

    public function addActiviadSubproyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $actividades = $this->input->post('actividades');
            $idSubProyecto = $this->input->post('idSubProyecto');

            $data = $this->m_proyecto->insertActividad($actividades, $idSubProyecto);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbSubProyectoActividad'] = $this->makeHTLMTablaActividadSubPro($this->m_proyecto->getAllSubproyectoActividad($idSubProyecto));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoSubProyectoActividad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('idSubProyectoAct');
            $subProyecto = $this->m_proyecto->getSubProyectoActividadInfo($id);
            $data['idSubProyectoActividad'] = $id;
            $data['idSubProyecto'] = $subProyecto['idSubProyecto'];
            $data['idActividad'] = $subProyecto['idActividad'];
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));

    }

    public function updateProyectoActividad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idSubProyecto = $this->input->post('selectSubProyecto2');
            $idActividad = $this->input->post('selectActividad');
            $id = $this->input->post('id');
            $exist = $this->m_proyecto->existSubProyectoActividad($idSubProyecto, $idActividad);
            if (!is_null($exist)) {
                throw new Exception('Ya se encuentra asociada esta actividad');
            }
            $data = $this->m_proyecto->editarSubProyectoActividad($id, $idSubProyecto, $idActividad);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar createCentral');
            }
            $data['tbSubProyectoActividad'] = $this->makeHTLMTablaActividadSubPro($this->m_proyecto->getAllSubproyectoActividad($idSubProyecto));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /////////////////////////////////////28092018///////////////////////////
    public function validaNombreSubProyecto()
    {
        try {
            $proyecto = $this->input->post('proyecto');
            $resultado = $this->m_proyecto->existeNombreProy($proyecto);
            $cant = $resultado;

            if ($cant >= 1) {
                $cant = 1;
            } else {
                $cant = 0;
            }
        } catch (Exception $e) {
            $cant = 1;
        }
        echo $cant;

    }

    public function getComboComplejidad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data['cmbTipoComplejidad'] = $this->makeHTMLTipoComplejidad($this->m_utils->getAllTipoComplejidad());
            $data['cmbTipoSubProyecto'] = $this->makeHTMLTipoSubProyecto($this->m_utils->getAllTipoSubProyecto());

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTMLTipoComplejidad($listaComplejidad, $idTipoComplejidad = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaComplejidad as $row) {
            $selected = ($row->idTipoComplejidad == $idTipoComplejidad) ? 'selected' : null;
            $html .= '<option value="' . $row->idTipoComplejidad . '" ' . $selected . ' >' . $row->complejidadDesc . '</option>';
        }

        return utf8_decode($html);
    }

    public function makeHTMLTipoSubProyecto($listaTipoSubProy, $idTipoSubProyecto = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaTipoSubProy as $row) {
            $selected = ($row->id_tipo_subproyecto == $idTipoSubProyecto) ? 'selected' : null;
            $html .= '<option value="' . $row->id_tipo_subproyecto . '" ' . $selected . ' >' . $row->descripcion . '</option>';
        }

        return utf8_decode($html);
    }
    
    
    
    public function makeHTMLTipoFactorMedicion($listaTipoFactorMedicion, $idPqtTipoFactorMedicion = null)
    {
        $html = '<option>Hola</option>';
    
        foreach ($listaTipoFactorMedicion as $row) {
//             $selected = ($row->idPqtTipoFactorMedicion == $idPqtTipoFactorMedicion) ? 'selected' : null;
//             $html .= '<option value="' . $row->idPqtTipoFactorMedicion . '" ' . $selected . ' >' . $row->descPqtTipoFactorMedicion . '</option>';
            $html .= $row->idPqtTipoFactorMedicion.'X';
        }
    
        return utf8_decode($html);
    }
    
    public function makeHTMLTablaFases($listaFases, $idSubProyecto)
    {
        $html = '';
    
        $html .= '<label>Cantidad de Item Plan por Fases:</label>
                                  <table id="tFases" class="ui-widget ui-widget-content">
                                    <thead>
                                      <tr class="ui-widget-header ">
                                        <th>Fase</th>
                                        <th>Cantidad Planificada</th>
                                        <th>Cantidad Registrada</th>
                                        <th>Editar</th>
                                      </tr>
                                    </thead>
                                    <tbody>';
        
        foreach ($listaFases as $row) {
            $html .= '<tr>';
            $html .= '<td>'.$row->fase.'</td>';
            $html .= '<td>'.$row->cantItemPlan.'</td>';
            $html .= '<td>'.$row->registrado.'</td>';
            $html .= '<td><a data-idSubProyecto ="'.$idSubProyecto.'" data-fase ="'.$row->fase.'" data-cantItemPlan="'.$row->cantItemPlan.'" onclick="abrirUpdFase($(this));">Actualizar</a></td>';
            // $html .= '<td><a data-id_subproyecto ="'.$idSubProyecto.'" data-id_fase ="'.$row->idFase.'" data-cantidad_plan="'.$row->cantItemPlan.'" onclick="openModalPlan($(this));">Actualizar Plan</a></td>';

            $html .= '</tr>';
        }

        $html .= '</tbody> </table>';
        
        return utf8_decode($html);
    }
    
    public function getInfoProyectoFasesPorSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id = $this->input->post('idProyecto');
            $tablaFases = $this->makeHTMLTablaFasesPorSubProyecto($id);
            $data['tablaFases'] = $tablaFases;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    
    }
    
    public function makeHTMLTablaFasesPorSubProyecto($idProyecto)
    {
        
        $maxFase = null;
        $minFase = null;
        $tempPlanificado = null;
        $tempRegistrado = null;
        
        foreach ($this->m_subproyecto_fases_cant_itemplan->getMaxMinFasesPorProyecto($idProyecto) as $row) {
            $minFase = $row->faseMin;
            $maxFase = $row->faseMax;
        }
        
        $listaProyectos = $this->m_subproyecto_fases_cant_itemplan->getSubProyectosPorProyecto($idProyecto);
        
        $html = '';
    
        $html .= '<div id="divTabla">
                  <table>
                  <thead>
                  <tr>
                  <th rowspan="3" >Id Sub Proyecto</th>
                  <th rowspan="3" >Sub Proyecto</th>';
        for($i=$minFase; $i<=$maxFase ;$i++){
            $html .= '<th colspan="2" >Fase '.$i.'</th>';
        }
        $html .= '</tr>';
        
        $html .= '<tr>';
        for($i=$minFase; $i<=$maxFase ;$i++){
            $html .= '<th>Cant. Planificada</th>';
            $html .= '<th>Cant. Registrada</th>';
        }
        $html .= '</tr>
                </thead>
                <tbody>';
        
        foreach ($listaProyectos as $row) {
            $html .= '<tr>';
            $html .= '<td>'.$row->idsubproyecto.'</td>';
            $html .= '<td>'.$row->subproyectoDesc.'</td>';
            for($i=$minFase; $i<=$maxFase ;$i++){
                $tempPlanificado = null;
                $tempRegistrado = null;
                
                $listaItems = $this->m_subproyecto_fases_cant_itemplan->getFasesPorSubProyectoFase($row->idsubproyecto, $i);
                
                foreach ($listaItems as $row2) {
                    $tempPlanificado = $row2->cantItemPlan;
                    $tempRegistrado = $row2->registrado;
                }
                $html .= '<td>'.$tempPlanificado.'</td>';
                $html .= '<td>'.$tempRegistrado.'</td>';
            }
            
            $html .= '</tr>';
        }
    
        $html .= '</tbody>
                  </table>
                  </div>';
    
        return utf8_decode($html);
    }
    
    function updCantFaseDeItemplan(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $txtNuevaCantidad = $this->input->post('txtNuevaCantidad');
            $idSubProyecto = $this->input->post('idSubProyecto');
            $fase = $this->input->post('fase');
            
            $data = $this->m_subproyecto_fases_cant_itemplan->upd_subproyecto_fases_cant_itemplan($idSubProyecto, $fase, $txtNuevaCantidad);
            $data['tFase'] = $this->makeHTMLTablaFases($this->m_subproyecto_fases_cant_itemplan->getFasesPorSubProyecto($idSubProyecto), $idSubProyecto);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataPlanificacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idFase        = $this->input->post('idFase');
            $idSubProyecto = $this->input->post('idSubProyecto');

            $tablaPlanifica = $this->getDataTablaPlanificacion($idSubProyecto, $idFase);
            $planTotal      = $this->m_utils->cantidadPlanTotal($idSubProyecto, $idFase);

            $data['tbPlanifica'] = $tablaPlanifica;
            $data['planTotal']   = $planTotal;

            $data['cmbMes'] = __buildCmbMes();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataTablaPlanificacion($idSubProyecto, $idFase) {
        $data = $this->m_utils->getDataPlanificacionItem($idSubProyecto, $idFase);

        $html = '<table id="data-table4" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NOMBRE PLAN</th>
                            <th>MES</th>
                            <th>CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data as $row) {

            $html .= ' <tr>
                            <td>' . $row['nombre_plan'] . '</td>
                            <td>' . $row['nombreMes'] . '</td>
                            <td>' . $row['cantidad'] . '</td>
                        </tr>';
            }
            $html .= '</tbody>
                </table>';

            return utf8_decode($html);
    }

    function insertPlanifica() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idFase   = $this->input->post('idFase');
            $idSubProyecto = $this->input->post('idSubProyecto');
            $nomPlan  = $this->input->post('nomPlan');
            $cantidad = $this->input->post('cantidad');
            $idMes    = $this->input->post('idMes');
            
            $fecha = $this->m_utils->fechaActual();
            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null || $idUsuario == '') {
                throw new Exception('Se finalizo la sesion, recargue la pagina.');
            }

            $data = $this->m_proyecto->insertPlanifica($idSubProyecto, $idFase, $nomPlan, $cantidad, $idMes, $fecha, $idUsuario);
            $tablaPlanifica = $this->getDataTablaPlanificacion($idSubProyecto, $idFase);

            $data['tbPlanifica'] = $tablaPlanifica;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
