<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_consulta_cotizacion_individual extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_diseno_cv');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('zip');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $data['tablaAnalisisEconomico'] = $this->getTablaCotizacionInd('','', '', '', '', '', '', '', '');
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
            $data['listaEECC']      = $this->m_utils->getAllEECC();
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 167, 185);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, 185, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                    $this->load->view('vf_cotizacion/v_consulta_cotizacion_individual',$data);
            }else{
                redirect('login','refresh');
            }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaCotizacionInd($sisego, $codigo, $idSubProyecto, $idSituacion, $idJefatura, $idEmpresaColab, $flgBandConf, $itemplan) {
		_log("ITEMPLAN: ".$itemplan);
        $data = $this->m_utils->getDataCotizacionIndividual($sisego, $codigo, NULL, $idSubProyecto, $idSituacion, $idJefatura, $idEmpresaColab, $flgBandConf, $itemplan);
		
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>C&Oacute;DIGO</th>
							<th>ITEMPLAN</th>
                            <th>SISEGO</th>
                            <th>CLIENTE</th>

  
                            <th>DEPARTAMENTO</th>
                            <th>SEGMENTO</th>
                            <th>ESTADO</th>
                            <th>TIPO REQ.</th>
                            <th>COSTO MAT.</th>
                            <th>COSTO MO</th>
                            <th>COSTO DISE&Ntilde;O</th>
                            <th>COSTO EXP. SEIA</th>
                            <th>COSTO ADICIONAL RURAL</th>
                            <th>COSTO TOTAL</th>
                         
                            <th>USUARIO ENVIO COTIZACI&Oacute;N</th>
                            <th>FECHA ENVIO COTIZACI&Oacute;N</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $btnDetalleSisego = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-assignment-o" data-codigo_cotizacion="'.$row['codigo_cluster'].'" title="Detalle" onclick="openModalDatosSisegos($(this))"></i>';
                 
                    $btnCvDoc =   '<a><i style="color:#A4A4A4;cursor:pointer" data-ubic_perfil="'.$row['ubic_perfil'].'" data-ubic_sisego="'.$row['ubic_sisego'].'"  data-codigo_cotizacion="'.$row['codigo_cluster'].'"
                                        data-ubic_rutas="'.$row['ubic_rutas'].'" class="zmdi zmdi-hc-2x zmdi-case-download" onclick="zipArchivosForm($(this));" title="Descargar Documentos"></i></a>';


                    $btnLog =   '<a><i style="color:#A4A4A4;cursor:pointer"   data-codigo_cotizacion="'.$row['codigo_cluster'].'"
                                        class="zmdi zmdi-hc-2x zmdi zmdi-eye" onclick="getLogCotizacionInd($(this));" title="Ver Log"></i></a>';
			
					if($this->session->userdata('idPerfilSession') == 4 && $row['itemplan'] != null) { //ADM
						$btnEditCosto =   '<a><i style="color:#A4A4A4;cursor:pointer"   data-codigo_cotizacion="'.$row['codigo_cluster'].'"
                                        class="zmdi zmdi-hc-2x zmdi zmdi-edit" onclick="getEditarCostosCotizacion($(this));" title="Editar Costos"></i></a>';
					} else {
						$btnEditCosto = NULL;
					}				
										
                    
                    $html .=' <tr>
                                <td>'.$btnCvDoc.' '.$btnDetalleSisego.' '.$btnLog.' '.$btnEditCosto.'</td>
                                <td>'.utf8_decode($row['codigo_cluster']).'</td>
								<td>'.utf8_decode($row['itemplan']).'</td>
                                <td>'.utf8_decode($row['sisego']).'</td>
                                <td>'.utf8_decode($row['cliente']).'</td>
                              
                                
                                
                                <td>'.utf8_decode($row['departamento']).'</td>
                                <td>'.utf8_decode($row['segmento']).'</td>
                                <td>'.utf8_decode($row['estado']).'</td>
                                <td>'.utf8_decode($row['tipo_requerimiento']).'</td>
                                <td>'.utf8_decode($row['costo_materiales']).'</td>
                                <td>'.utf8_decode($row['costo_mano_obra']).'</td>
                                <td>'.utf8_decode($row['costo_diseno']).'</td>
                                <td>'.utf8_decode($row['costo_expe_seia_cira_pam']).'</td>
                                <td>'.utf8_decode($row['costo_adicional_rural']).'</td>
                                <td>'.utf8_decode($row['costo_total']).'</td>
                              
           
                                <td>'.utf8_decode($row['nombreUsuarioEnvioCoti']).'</td>
                                <td>'.utf8_decode($row['fecha_envio_cotizacion']).'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function filtrarTablaConsultaCotizacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;

        try{ 
            $sisego = $this->input->post('sisego');
            $codigo = $this->input->post('codigo_form');
            $idSubPro       = $this->input->post('idSubPro');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');
            $idSituacion    = $this->input->post('idSituacion');
            $flgBandConf    = $this->input->post('flgBandConf');
			$itemplan       = $this->input->post('itemplan');

            $idSubProyecto  = ($idSubPro       == '') ? NULL : $idSubPro;
            $idEmpresaColab = ($idEmpresaColab == '') ? NULL : $idEmpresaColab;
            $idSituacion    = ($idSituacion    == '') ? NULL : $idSituacion;
            $idJefatura     = ($idJefatura     == '') ? NULL : $idJefatura;
            $flgBandConf    = ($flgBandConf     == '') ? NULL : $flgBandConf;
			$itemplan       = ($itemplan     == '')    ? NULL : $itemplan;
            $sisego = ($sisego == '') ? NULL : $sisego;
            $codigo = ($codigo == '') ? NULL : $codigo;
            
            $data['tablaBandeja'] = $this->getTablaCotizacionInd($sisego, $codigo, $idSubProyecto, $idSituacion, $idJefatura, $idEmpresaColab, $flgBandConf, $itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getDataDetalleCotizacionSisego() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            
            $codigo_cot = $this->input->post('codigo_cot');

            if($codigo_cot == null) {
                throw new Exception('itemplan null, comunicarse con el programador');
            }

            $arrayPlanObra = $this->m_utils->getDataCotizacionIndividual(NULL, $codigo_cot, 1);

            $html = '  <div class="card">
                            <div class="card-header; container form-group" style="background:var(--celeste_telefonica);color:white;">
                                DATOS SISEGO
                            </div>
                            <div class="card-body container">
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label>OPERADOR: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['operador'])).'</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>TIPO DISE&Ntilde;O:</label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['tipo_diseno_desc'])).'</label>
                                    </div>
                                     <div class="col-md-4">
                                        <label>TIPO ENLACE:</label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['tipo_enlace'])).'</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label>ACCESO CLIENTE: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['acceso_cliente'])).'</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>TENDIDO EXTERNO: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['tendido_externo'])).'</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label>DURACI&Oacute;N: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['duracion'])).'</label>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label>NOMBRE ESTUDIO: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['nombre_estudio'])).'</label>
                                    </div>
                                     <div class="col-md-6">
                                        <label>TIPO CLIENTE: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['tipo_cliente'])).'</label>
                                    </div>
                                </div>
								<div class="row form-group">
                                    <div class="col-md-6">
                                        <label>NOMBRE EBC: </label>
                                        <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['nom_ebc'])).'</label>
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <div class="card">
                            <div class="card-header; container form-group" style="background:var(--celeste_telefonica);color:white;">
                                DATOS FORMULARIO COTIZACI&Oacute;N
                            </div>
                            <div class="card-body container">
                            
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>NODO PRINCIPAL: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['nodo_principal'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>NODO RESPALDO: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['nodo_respaldo'])).'</label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>FACILIDADES DE RED: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['facilidades_de_red'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>CANT. CTO: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['cant_cto'])).'</label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>METRO TENDIDO A&Eacute;REO: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['metro_tendido_aereo'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>METRO TENDIDO SUBTERRANEO: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['metro_tendido_subterraneo'])).'</label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>METRO CANALiZACI&Oacute;N: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['metors_canalizacion'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>CANT. C&Aacute;MARAS NUEVAS: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['cant_camaras_nuevas'])).'</label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>CANT. POSTES NUEVOS: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['cant_postes_nuevos'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>CANT. POSTES APOYO: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['cant_postes_apoyo'])).'</label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>CANT. APERTURA C&Aacute;MARA: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['cant_apertura_camara'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>REQUIERE SEIA: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['requiere_seia'])).'</label>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>REQUIERE APROb. MML MTC: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['requiere_aprob_mml_mtc'])).'</label>
                                        </div>
                                         <div class="col-md-6">
                                            <label>REQUIERE APROB. INC.: </label>
                                            <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['requiere_aprob_inc'])).'</label>
                                        </div>
                                    </div>
                           
                            </div>
                            <div class="col-md-12">
                                <label>COMENTARIO.: </label>
                                <label style="color:blue">'.utf8_decode(strtoupper($arrayPlanObra['comentario'])).'</label>
                            </div>
                        </div>';
            $data['dataInfoSisego'] = $html;
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function zipArchivosForm() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $codigo_cot = $this->input->post('codigo_cot');
            
            if($codigo_cot == null || $codigo_cot == '') {
                throw new Exception('accion no permitida');
            }
            
            $ubicacion = 'uploads/sisego/cotizacion_individual/'.$codigo_cot;

            $this->zip->read_dir($ubicacion,false);
            
            $fileName = $codigo_cot.'_archivos_cotizacion.zip';
             
            $ubicZip = $ubicacion.'/'.$fileName;   
            $this->zip->archive($ubicZip);
            //$this->rrmdir($ubicacion);
           

            $data['directorioZip'] =  $ubicacion.'/'.$fileName;

            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     function comprimirFiles($itemplan) {
        $ubicacion = 'uploads/evidencia_paralizacion/'.$itemplan;
        $this->zip->read_dir($ubicacion,false);
        
        $fileName = $itemplan.'_'.rand(1, 100).date("dmhis").'.zip';   
        $ubicZip = 'uploads/evidencia_paralizacion/'.$fileName;   
        $this->zip->archive($ubicZip);
        $this->rrmdir($ubicacion);
        return $ubicZip;
    }

    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
    function getLogCotizacionInd() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = '';
        try {
            $codigo_cluster = $this->input->post('codigo_cluster');

            if($codigo_cluster == null) {
                throw new Exception('error, c&oacute;digo cluster null');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablaLog'] = $this->getTablaLogCotizacionInd($codigo_cluster);
        } catch(Exception $e) {
            $data['msj']= $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    function getTablaLogCotizacionInd($codigo_cluster) {
        $data = $this->m_utils->getLogCotizacionInd($codigo_cluster);

        $html = '<table id="data-table_log" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>C&Oacute;DIGO</th>
                            <th>ESTADO</th>
                            <th>USUARIO</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $html .=' <tr>
                                <td>'.utf8_decode($row['codigo_cluster']).'</td>
                                <td>'.utf8_decode($row['accion']).'</td>
                                <td>'.utf8_decode($row['nombre']).'</td>
                                <td>'.utf8_decode($row['fecha']).'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }
	
	function getEditarCostosCotizacion() {
		$data['error'] = EXIT_ERROR;
        $data['msj']   = '';
        try {
            $codigo_cluster = $this->input->post('codigo_cluster');

            if($codigo_cluster == null) {
                throw new Exception('error, c&oacute;digo cluster null');
            }
			
			$dataCoti = $this->m_utils->getRowCotizacion($codigo_cluster);
			
			$data['error'] = EXIT_SUCCESS;
			$data['dataCoti'] = $dataCoti;
        } catch(Exception $e) {
            $data['msj']= $e->getMessage();
        }
        echo json_encode($data);
	}
	
	function actualizarCostosCotiPo(){
		$data['error'] = EXIT_ERROR;
        $data['msj']   = '';
        try {
            $codigo_cluster = $this->input->post('codigo_cluster');
			$costoMat 		= $this->input->post('costoMat');
			$costoMatEdif 	= $this->input->post('costoMatEdif');
			$costoMatOc 	= $this->input->post('costoMatOc');
			
			$costoMo 		= $this->input->post('costoMo');
			$costoDiseno 	= $this->input->post('costoDiseno');
			$costoEia 		= $this->input->post('costoEia');
			$costoRural 	= $this->input->post('costoRural');
			$costoOcMO 		= $this->input->post('costoOcMO');
			$costoTotalMo 	= $this->input->post('costoTotalMo');
			$costoTotalMat 	= $this->input->post('costoTotalMat');

            if($codigo_cluster == null) {
                throw new Exception('error, c&oacute;digo cluster null');
            }
			if($costoTotalMo == 0 || $costoTotalMat == 0) {
                throw new Exception('El costo total MO o MAT no puede ser cero.');
            }
			
			$arrayCostosCoti = array(
										"costo_materiales" 		   => $costoMat,
										"costo_mat_edif"   		   => $costoMatEdif,
										"costo_oc_edif"    		   => $costoMatOc,
										"costo_mano_obra"  		   => $costoMo,
										"costo_diseno"             => $costoDiseno,
										"costo_expe_seia_cira_pam" => $costoEia,
										"costo_adicional_rural"    => $costoRural,
										"costo_oc"                 => $costoOcMO
									);
			
			$data = $this->m_utils->updateCostosCoti($costoTotalMo, $costoTotalMat, $arrayCostosCoti, $codigo_cluster);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
        } catch(Exception $e) {
            $data['msj']= $e->getMessage();
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