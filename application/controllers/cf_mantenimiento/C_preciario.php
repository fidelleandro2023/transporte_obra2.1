<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_preciario extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_mantenimiento/m_preciario');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_PRECIARIO);
            $data['title'] = 'REGISTRAR KIT MATERIAL PLANTA EXTERNA';
            $data['cmbZonal']     = __buildComboZonal('cmbZonal', null);

            $data['cmbEcc']       = __buildComboEcc('cmbEcc', null, $idEcc, null);
            $data['cmbEstacion']  = __buildComboEstacionAll('cmbEstacion', null, null);
            $data['cmbTipoCosto'] = __buildComboTipoCostoAll('cmbTipoCosto', null, null);

            $data['opciones'] = $result['html'];
            $this->load->view('vf_mantenimiento/v_preciario',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getPreciarioTb2019() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idZonal        = $this->input->post('idZonal');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idPrecioDiseno = $this->input->post('idTipoCosto');
            $idEstacion     = $this->input->post('idEstacion');

            // if($idEmpresaColab == null) {
            //     throw new Exception('error No se encontr&oacute; idZonal, comunicarse con el programador');
            // }

            // if($idZonal == null) {
            //     throw new Exception('error No se encontr&oacute; idEmpresaColab, comunicarse con el programador');
            // }

            $data['tablaPreciario'] = $this->tablaPreciario($idEmpresaColab, $idZonal, $idPrecioDiseno, $idEstacion);
            $data['tablaZonal']     = $this->tablaZonal();
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaPreciario($idEmpresaColab, $idZonal, $idTipoCosto, $idEstacion) {
        $idEmpresaColab = ($idEmpresaColab == '') ? null : $idEmpresaColab;
        $idZonal        = ($idZonal == '') ? null : $idZonal;
        $idTipoCosto    = ($idTipoCosto == '') ? null : $idTipoCosto;
        $idEstacion     = ($idEstacion == '') ? null : $idEstacion;

        $dataPreciario = $this->m_preciario->getPreciarioData($idEmpresaColab, $idZonal, $idTipoCosto, $idEstacion);
        $html = '<table id="tbPreciario" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>ECC</th>
                            <th>ESTACION</th> 
                            <th>ZONAL</th>
                            <th>TIPO COSTO</th>                     
                            <th>COSTO</th>        
                            <th>ACCI&Oacute;N</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($dataPreciario as $row){
            $cont++;
            // $checked  = ($row['kitIdMaterial'] == 1) ? 'checked' : null;
            // $disabled = ($row['kitIdMaterial'] == 1) ? 'disabled' : null;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['empresaColabDesc'].'</td>
                            <td style="background:'.$style.'">'.$row['estacionDesc'].'</td>
                            <th style="background:'.$style.'">'.$row['zonalDesc'].'</th>
                            <td style="background:'.$style.'">'.$row['descPrecio'].'</td>
                            <td style="background:'.$style.'">'.$row['costo'].'</td>								
                            <td><i class="zmdi zmdi-hc-2x zmdi-delete" style="cursor:pointer" onclick="openModalEliminarMat($(this));"></i></td>                                                 		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function getZonalTb() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            // $idZonal        = $this->input->post('idZonal');
            // $idEmpresaColab = $this->input->post('idEmpresaColab');
            // $idEstacion     = $this->input->post('idEstacion');

            // if($idEmpresaColab == null) {
            //     throw new Exception('error No se encontr&oacute; idZonal, comunicarse con el programador');
            // }

            // if($idZonal == null) {
            //     throw new Exception('error No se encontr&oacute; idEmpresaColab, comunicarse con el programador');
            // }

            $data['tablaZonal'] = $this->tablaZonal();
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaZonal() {
        $dataZonal = $this->m_utils->getAllZonal();
        $html = '<table id="tbZonal" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>ZONAL</th>
                            <th>ACCI&Oacute;N</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($dataZonal->result() as $row){
            $cont++;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row->zonalDesc.'</td>		
                            <td><i style="cursor:pointer" data-id_zonal="'.$row->idZonal.'" onclick="openModalCosto($(this))" class="zmdi zmdi-hc-2x zmdi-plus-circle-o"></i></td>                                                 		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function insertZonal() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $costo          = $this->input->post('costo');

            $idZonalfiltro        = $this->input->post('idZonalfiltro');
            $idEmpresaColabFiltro = $this->input->post('idEmpresaColabFiltro');
            $idPrecioFiltro       = $this->input->post('idTipoCostoFiltro');
            $idEstacionFiltro     = $this->input->post('idEstacionFiltro');
            
            $idEmpresaColabFiltro = ($idEmpresaColabFiltro == '') ? null : $idEmpresaColabFiltro;
            $idZonalfiltro        = ($idZonalfiltro == '')       ? null : $idZonalfiltro;
            $idPrecioFiltro       = ($idPrecioFiltro == '')      ? null : $idPrecioFiltro;
            $idEstacionFiltro     = ($idEstacionFiltro == '')    ? null : $idEstacionFiltro;

            $idZonal        = $this->input->post('idZonal');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idPrecioDiseno = $this->input->post('idTipoCosto');
            $idEstacion     = $this->input->post('idEstacion');
            
            $idEmpresaColab = ($idEmpresaColab == '') ? null : $idEmpresaColab;
            $idZonal        = ($idZonal == '')        ? null : $idZonal;
            $idPrecioDiseno = ($idPrecioDiseno == '') ? null : $idPrecioDiseno;
            $idEstacion     = ($idEstacion == '')     ? null : $idEstacion;
    

            _log($idEmpresaColab);
            _log($idEstacion);
            _log($idPrecioDiseno);

            if($idZonal == null || $costo == null) {
                throw new Exception('error variable NULL');
            }

            if($idEmpresaColab == null || $idPrecioDiseno == null || $idEstacion == null) {
                throw new Exception('error variable NULL');
            }



            $count = $this->m_preciario->countPreciario($idEmpresaColab, $idZonal, $idPrecioDiseno, $idEstacion);

            if($count == 1) {
                throw new Exception('ya esta registrado esta zonal');
            } 

            $arrayInsert = array (
                                    'idZonal'        => $idZonal,
                                    'idEmpresaColab' => $idEmpresaColab,
                                    'idPrecioDiseno' => $idPrecioDiseno,
                                    'costo'          => $costo,
                                    'idEstacion'     => $idEstacion
                                 );
            $data = $this->m_preciario->insertPreciario($arrayInsert);
            $data['tablaPreciario'] = $this->tablaPreciario($idEmpresaColabFiltro, $idZonalfiltro , $idPrecioFiltro, $idEstacionFiltro);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function eliminarMaterial() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubProyecto    = $this->input->post('idSubProyecto');
            $idMaterial       = $this->input->post('idMaterial');
            $idEstacion       = $this->input->post('idEstacion');

         
            if($idSubProyecto == null) {
                throw new Exception('error No se encontr&oacute; subproyecto, comunicarse con el programador');
            }

            if($idMaterial == null) {
                throw new Exception('error No se encontr&oacute; material, comunicarse con el programador');
            }

            if($idEstacion == null) {
                throw new Exception('error No se encontr&oacute; estacion, comunicarse con el programador');                
            }

            $data = $this->m_kit_planta_externa->eliminarMaterial($idSubProyecto, $idMaterial, $idEstacion);
            $data['tablaKit']      = $this->tablakit($idSubProyecto, $idEstacion);
            $data['tablaMaterial'] = $this->tablaMaterial($idSubProyecto, $idEstacion);
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getCmbsPreciario() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idEmpresaColabFiltro = $this->input->post('idEmpresaColabFiltro');
            $idPrecioFiltro       = $this->input->post('idTipoCostoFiltro');
            $idEstacionFiltro     = $this->input->post('idEstacionFiltro');
            
            $idEmpresaColabFiltro = ($idEmpresaColabFiltro == '') ? null : $idEmpresaColabFiltro;
            $idPrecioFiltro       = ($idPrecioFiltro == '')      ? null : $idPrecioFiltro;
            $idEstacionFiltro     = ($idEstacionFiltro == '')    ? null : $idEstacionFiltro;

            $idEcc     = $this->session->userdata('eeccSession');

            $data['cmbEcc']       = __buildComboEcc('cmbEccIngresa', null, $idEcc, $idEmpresaColabFiltro);
            $data['cmbEstacion']  = __buildComboEstacionAll('cmbEstacionIngresa', null, $idEstacionFiltro);
            $data['cmbTipoCosto'] = __buildComboTipoCostoAll('cmbTipoCostoIngresa', null, $idPrecioFiltro);
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}