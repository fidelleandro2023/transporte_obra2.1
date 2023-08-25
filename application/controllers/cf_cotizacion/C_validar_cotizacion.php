<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_validar_cotizacion extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cotizacion/m_validar_cotizacion');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {


            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_validar_cotizacion->getItemplanPreRegistro());
            //$data['listaEstaciones'] = $this->m_utils->getAllEstacionNoDiseno();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_COTIZACION, ID_PERMISO_HIJO_VALIDAR_COTIZACION);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, ID_PERMISO_HIJO_VALIDAR_COTIZACION, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_cotizacion/v_validar_cotizacion', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTMLTablaEvidencias($listaEvidencias) {
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Itemplan</th>
                            <th>Nombre archivo</th>
                            <th>Fecha Registro</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
          
                    <tbody>';
        if ($listaEvidencias != null) {
            foreach ($listaEvidencias->result() as $row) {

                $html .= ' <tr>
                         <td></td>
							<td>' . $row->itemplan . '</td>
							<td><a href="' . base_url() . '\\uploads\\evidencias\\' . $row->itemplan . '\\' . $row->file_name . '">' . $row->file_name . '</a></td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->usuario . '</td>
						</tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function makeHTLMTablaBandejaAprobMo($listaPTR) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:5%"></th>                            
                            <th>Item Plan</th>  
                            <th>Monto MO</th>
                            <th>Monto MAT</th>
                            <th>Monto Total</th>
                            <th>Proyecto</th>  
                            <th>Sub Proyecto</th>
                            <th>EECC</th>
                            <th>Jefatura</th>
                            <th>Region</th>        
                            <th>Estado Plan</th>
                            <th>Fecha Envio.</th>
                            <th>Usuario Envio.</th>
                            <th></th>
                        </tr>
                    </thead>
                   
                    <tbody>';
        if ($listaPTR != null) {
            foreach ($listaPTR->result() as $row) {

                $html .= ' <tr>
                            <td>                                                                   
                                ' . (($row->estado != 1) ? '<a href="' . $row->ruta_pdf . '" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/pdf.png"></a>' : '') . '
                            </td>
							<td>' . $row->itemplan . '</td>	
							<td>' . $row->monto_mo . '</td>
						    <td>' . $row->monto_mat . '</td>
					        <td>' . $row->monto_total . '</td>
							<td>' . $row->proyectoDesc . '</td>	
                            <td>' . $row->subProyectoDesc . '</td>	
                            <td>' . $row->empresaColabDesc . '</td>	
                            <td>' . $row->jefatura . '</td>		
                            <td>' . $row->region . '</td>								
                            <td>' . $row->estadoPlanDesc . '</td>   
                            <td>' . $row->fecha_creacion . '</td>
                            <td>' . $row->usuario_envio_cotizacion . '</td>
                            <td style="text-align: center;">                               
                                    <a data-itemplan="' . $row->itemplan . '" onclick="devolverCotizacion(this);"><i class="zmdi zmdi-hc-2x zmdi-mail-reply-all"></i></a>
                                    <a data-itemplan="' . $row->itemplan . '" onclick="aceptarCotizacion(this);"><i class="zmdi zmdi-hc-2x zmdi-check-all"></i></a>
                                    <a data-itemplan="' . $row->itemplan . '" onclick="rechazarCotizacion(this);"><i class="zmdi zmdi-hc-2x zmdi-close-circle"></i></a>
                                    
                            </td>		
						</tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function filtrarTabla() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $itemPlan = $this->input->post('itemplanFil');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_validar_cotizacion->getItemplanList($itemPlan, $SubProy));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function insertEvidenciaByItemplan() {
        //$itemplan =  $this->session->userdata('itemplanEvi');
        $itemplan = $this->input->post('itemplan');

        $file = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];

        $subCarpeta = 'uploads/cotizacion/' . $itemplan . '/';


        $file2 = utf8_decode($file); //le generamos un nombreAleatorio

        if (!is_dir($subCarpeta))
            mkdir($subCarpeta, 0777);
        if (utf8_decode($file) && move_uploaded_file($_FILES ["file"] ["tmp_name"], $subCarpeta . $file2)) {
            $this->m_validar_cotizacion->saveFileCotizacion($itemplan, $subCarpeta . $file2);
        }

        $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_validar_cotizacion->getItemplanPreRegistro());
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function zipTempFiles() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemplan = $this->session->userdata('itemplanEvi');
            $subCarpeta = $this->session->userdata('subCarpetaEvi');
            $this->zip->read_dir($subCarpeta, false);
            $fileName = $itemplan . '_' . rand(1, 100) . date("dmhis") . '.zip';
            $this->zip->archive('uploads/evidencias/' . $itemplan . '/' . $fileName);
            $data = $this->m_validar_cotizacion->saveItemplanEvidencia($itemplan, $fileName);
            $this->rrmdir($subCarpeta);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function rrmdir($src) {
        $dir = opendir($src);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    function validarCotizacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        $data['tipo_mensaje'] = 0;
        try {
            $itemplan = $this->input->post('itemplan');
            $accion = $this->input->post('accion');



            if ($accion == 1) {//APROBAR
                /////////////////////////////AUTO ADJUDICAR ITEMPLAN
                $infoPlan = $this->m_validar_cotizacion->getInfoItemplan($itemplan);
                $infoCotizacion = $this->m_validar_cotizacion->getInfoCotizacion($itemplan);
                $monto_mo = 0;
                $monto_mat = 0;
                if ($infoCotizacion != null) {
                    $monto_mo = $infoCotizacion['monto'];
                    $monto_mat = $infoCotizacion['monto_mat'];
                } else {
                    $monto_mo = 0;
                    $monto_mat = 0;
                }

                ///////// Ivan - Creacion de OC Opex

               if ($infoPlan['flg_opex'] == 2) {
                    $counOpex = $this->m_validar_cotizacion->countOpex($infoPlan['idSubProyecto']);
                    log_message('error', $counOpex);
                    if ($counOpex > 0) {

                        $monto = $this->m_validar_cotizacion->getMOplanObra($itemplan);
                        $dataOpex = $this->m_validar_cotizacion->getOpex($infoPlan['idSubProyecto'], $monto);
                        if (count($dataOpex) === 1) {
                            $dataCotizacion = array("itemplan" => $itemplan,
                                "usua_aprueba_cotizacion" => $this->session->userdata('userSession'),
                                "fecha_aprueba_cotizacion" => date("Y-m-d H:i:s"),
                                "estado" => 4//cotizacion aprobada
                            );

                            $dataPlanobra = array("itemplan" => $itemplan,
                                "costo_unitario_mo" => $monto_mo,
                                "costo_unitario_mat" => $monto_mat,
                                "costo_unitario_mo_crea_oc" => $monto_mo,
                                "costo_unitario_mat_crea_oc" => $monto_mat
                            );
                            $data = $this->m_validar_cotizacion->aprobarCotizacionOpex($dataCotizacion, $dataPlanobra, $itemplan, $dataOpex[0]->idOpex, $this->session->userdata('idPersonaSession'));
                            $data['error'] = EXIT_SUCCESS;
                        } else {
                            throw new Exception('Cuenta OPEX sin MONTO DISPONIBLE');
                        }
                    } else {
                        throw new Exception('No tiene cuenta OPEX registrada');
                    }
                } else {


                    //////////


                    $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($infoPlan['idSubProyecto']);

                    //  if($dias!=null){//tiene Adjudicacion Automatica

                    $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplan);
                    $this->session->set_userdata('has_fo', $conEsta['fo']);
                    $this->session->set_userdata('has_coax', $conEsta['coaxial']);

                    $curHour = date('H');
                    if ($curHour >= 13) {//13:00 PM
                        $dias = ($dias + 1);
                    }
                    $fecha = date('Y-m-j');
                    $nuevafecha = strtotime('+' . $dias . ' day', strtotime($fecha));
                    $nuevafecha = date('Y-m-j', $nuevafecha);
                    /* ya no debe crearse en pre registro hasta que carguen la OC.
                      $data = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplan, $infoPlan['idSubProyecto'], $infoPlan['idCentral'], $infoPlan['idEmpresacolab'], null, $nuevafecha);

                      if($data['error']  == EXIT_ERROR){
                      throw new Exception('ERROR INTERNO!');
                      }
                     */
                    /*                     * LOGICA PARA OBTENER LA PEP PAR LA CREACION DE LA SOLICITUD DE OC* */
                    $listaPepNoPPT = array();
                    $hasSomePep = false;
                    $hasSomePepWiPresu = false;
                    $pep1 = null;
                    $monto_tmp_final = 0;
                    $itemPepGrafo = $this->m_validar_cotizacion->getPEPSITemplanPep2GrafoByItemplan($itemplan);
                    if (count($itemPepGrafo) > 0) {
                        foreach ($itemPepGrafo as $pep) {
                            if ($pep->monto_temporal >= $monto_mo) {
                                $hasSomePepWiPresu = true;
                                $pep1 = $pep->pep1;
                                $monto_tmp_final = ($pep->monto_temporal - $monto_mo);
                                break;
                            } else {
                                array_push($listaPepNoPPT, $pep->pep1);
                            }
                        }
                        $hasSomePep = true;
                    }

                    if (!$hasSomePepWiPresu) {
                        $itemBolsaPep = $this->m_validar_cotizacion->getPEPSBolsaPepByItemplan($itemplan);
                        if (count($itemBolsaPep) > 0) {
                            foreach ($itemBolsaPep as $pep) {
                                if ($pep->monto_temporal >= $monto_mo) {
                                    $hasSomePepWiPresu = true;
                                    $pep1 = $pep->pep1;
                                    $monto_tmp_final = ($pep->monto_temporal - $monto_mo);
                                    break;
                                } else {
                                    array_push($listaPepNoPPT, $pep->pep1);
                                }
                            }
                            $hasSomePep = true;
                        }
                    }

                    if (!$hasSomePep) {
                        throw new Exception('La obra no cuenta con PEP configurada.');
                    } else {
                        if ($hasSomePepWiPresu) {//se aprueba la cotizacion y generas oc y toda la vaina
                            $codigo_solicitud = $this->m_utils->getNextCodSolicitud();
                            if ($codigo_solicitud == null) {
                                throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP');
                            }

                            $dataCotizacion = array("itemplan" => $itemplan,
                                "usua_aprueba_cotizacion" => $this->session->userdata('userSession'),
                                "fecha_aprueba_cotizacion" => date("Y-m-d H:i:s"),
                                "estado" => 4//cotizacion aprobada
                            );

                            $dataPlanobra = array(	"itemplan" 						=> $itemplan,
													"costo_unitario_mo" 			=> $monto_mo,
													"costo_unitario_mat" 			=> $monto_mat,
													"solicitud_oc" 					=> $codigo_solicitud,
													"estado_sol_oc" 				=> 'PENDIENTE',
													"costo_unitario_mo_crea_oc" 	=> $monto_mo,
													"costo_unitario_mat_crea_oc" 	=> $monto_mat,
													"fec_registro_sol_creacion_oc" 	=> $this->fechaActual()
                            );

                            $solicitud_oc_creacion = array('codigo_solicitud' => $codigo_solicitud,
                                'idEmpresaColab' => $infoPlan['idEmpresaColab'],
                                'estado' => 1, //pendiente
                                'fecha_creacion' => $this->fechaActual(),
                                'idSubProyecto' => $infoPlan['idSubProyecto'],
                                'plan' => 'COTIZACION',
                                'pep1' => $pep1,
                                'pep2' => $pep1 . '-001',
                                'estatus_solicitud' => 'NUEVO',
                                'tipo_solicitud' => 1//creacion                                            
                            );

                            $item_x_sol = array('itemplan' => $itemplan,
                                'codigo_solicitud_oc' => $codigo_solicitud,
                                'costo_unitario_mo' => $monto_mo
                            );

                            $dataSapDetalle = array('monto_temporal' => $monto_tmp_final,
                                'pep1' => $pep1
                            );

                            $data = $this->m_validar_cotizacion->aprobarCotizacionSolo($dataCotizacion, $dataPlanobra, $solicitud_oc_creacion, $item_x_sol, $dataSapDetalle);
                            $data['error'] = EXIT_SUCCESS;
                            log_message('error', 'aprobados todo...!: PEP = ' . $pep1);
                        } else {
                            $html = '';
                            foreach ($listaPepNoPPT as $pp) {
                                $html .= '<a>' . $pp . ' : SIN PRESUPUESTO</a><br>';
                            }
                            $data['error'] = EXIT_SUCCESS;
                            $data['tipo_mensaje'] = 1;
                            $data['html'] = $html;
                        }
                    }

                    /////////
                }

                //////// -- Fin Ivan - Creacion de OC Opex
            } else if ($accion == 2) {//RECHAZAR 
                $data = $this->m_validar_cotizacion->rechazarCotizacion($itemplan);
            } else if ($accion == 3) {//DEVOLVER 
                $data = $this->m_validar_cotizacion->devolverCotizacion($itemplan);
            }

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_validar_cotizacion->getItemplanPreRegistro());
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
