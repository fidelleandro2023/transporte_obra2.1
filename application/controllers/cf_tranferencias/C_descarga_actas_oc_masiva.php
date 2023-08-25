<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_descarga_actas_oc_masiva extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
		$this->load->library('excel');
        $this->load->helper('url');
        $this->load->library('zip');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            // Trayendo zonas permitidas al usuario
            $arrayIdActidad = (isset($_GET['arryAct']) ? $_GET['arryAct'] : '');
            if ($arrayIdActidad != null) {
                $listaProyEstPart = $this->m_utils->getAllProyEstPartida(null, null, null, $arrayIdActidad);
            } else {
                $listaProyEstPart = '';
            }
            $zonas = $this->session->userdata('zonasSession');
          
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');

            $data['cmbEmpresaColab'] = __buildCmbEmpresaColab();
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PROY_EST_PARTIDA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_GESTIONAR_ORDEN_COMPRA, ID_MODULO_ADMINISTRATIVO);
			$data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_tranferencias/v_descarga_actas_oc_masiva', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    function getTablaDescarga() {
        $fechaInicio    = $this->input->post('fechaInicio');
        $fechaFin       = $this->input->post('fechaInicio');
        $idEmpresaColab = $this->input->post('idEmpresaColab');

        $this->tablaDescarga($idEmpresaColab, $fechaInicio, $fechaFin);
    }

    function tablaDescarga($idEmpresaColab, $fechaInicio, $fechaFin) {
        $dataArray = $this->m_utils->getActasOc($idEmpresaColab, $fechaInicio, $fechaFin);

        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <!--<th></th>-->
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
							    <th>NOMBRE PROYECTO</th>
                                <th>COSTO MO</th>
                                <th>CESTA</th>
                                <th>OC</th>
                                <th>POSICION</th>
                            </tr>
                        </thead>
    
                        <tbody>';
        $indice = 0;
        $array_valido = array();
            foreach($listaEstado as $row){
                $html .='<tr id="tr'.$indice.'">
                            <!--td style="width: 5px;">'.(($estado==2) ? '<a style="cursor:pointer;" data-indice="'.$indice.'"data-solicitud_oc="'.$row->solicitud_oc.'" onclick="removeTRreservado(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</td>-->               
                            <td>'.$row->itemPlan.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->nombreProyecto.'</td>
                            <td>'.$row->costo_unitario_mo_certi.'</td>
							<td>'.$row->cesta.'</td>
							<td>'.$row->orden_compra.'</td>
                            <td>'.$row->posicion.'</td>
                          </tr>';
                $indice++;
                array_push($array_valido, $row->solicitud_oc.'|'.$row->itemPlan);
            }
       
         
        $html .='</tbody>
                </table>';       
        
        return  utf8_decode($html);
    }

    function getActaCertificacionMasivo() {_log("ENTRO11");
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            $arrayJson = array();
            $arrayItemplan = array();
            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;
            $flgPartidasNoExist = 0;

            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }
            $pathItemplan = 'uploads/actasOc';
            
            $files = glob('uploads/actasOc/*'); //obtenemos todos los nombres de los ficheros
            foreach($files as $file){
                if(is_file($file))
                unlink($file); //elimino el fichero
            }

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $itemplan = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						$codigo_solicitud = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$orden_compra = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        
                        if($itemplan != null) {
                            //array_push($arrayItemplan, $itemplan);
                            $this->formatoPdfActas($itemplan, $codigo_solicitud, $orden_compra);
                        } else if($codigo_solicitud != null) {
							$this->formatoPdfActas($itemplan, $codigo_solicitud, $orden_compra);
						} else if($codigo_solicitud != null) {
							$this->formatoPdfActas($itemplan, $codigo_solicitud, $orden_compra);
						}
                    }
                }
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function formatoPdfActas($itemplan, $codigo_solicitud, $orden_compra){
		$fechaActual = $this->m_utils->fechaActual();
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Acta Certificacion');
        $pdf->SetHeaderMargin(30);
        //$pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // add a page
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        
		$itemplan         = ($itemplan) ? $itemplan : NULL;
		$codigo_solicitud = ($codigo_solicitud) ? $codigo_solicitud : NULL;
		$orden_compra     = ($orden_compra) ? $orden_compra : NULL;
		
        $dataSolicitud = $this->m_utils->getDataActaCerti($itemplan, $codigo_solicitud, $orden_compra);
		// _log(print_r($dataSolicitud, true));
		$img = base_url().'public/img/logo/tdp.png';
        $tbl =' <img style="width: 100px; heigth:40px" src="">
                <p style="text-align: center;"><strong>ACTA CERTIFICACI&Oacute;N</strong></p>
                <p style="text-align: center;">&nbsp;</p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%;" style="text-align: left;background-color:#F2F27C"><strong><BR>T&Iacute;TULO DEL PROYECTO / OBRA: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['proyectoDesc'].'</strong></td>
                            <td style="width: 25%;" style="text-align: left;background-color:#F2F27C"><strong><BR>FECHA: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.date("d/m/Y",  strtotime($fechaActual)).'</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%;" style="text-align: left;background-color:#F2F27C"><strong><BR><BR>GERENCIA:</strong></td>
                            <td style="width: 25%;" style="text-align: left;"><strong>'.$dataSolicitud['gerencia_desc'].'</strong></td>
                            <td style="width: 25%; background-color:#F2F27C; text-align: left;"><strong><BR><BR>GESTOR RESPONSABLE: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['responsable'].'</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>PROVEEDOR: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['empresaColabDesc'].'</strong></td>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>POSICIONES A CERTIFICAR: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['group_posicion'].'</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>IMPORTE TOTAL DE LA OC: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>S/.'.$dataSolicitud['costo_sap'].'</strong></td>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>IMPORTE A CERTIFICAR: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>S/.'.$dataSolicitud['costo_sap'].'</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>NRO O/C: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['orden_compra'].'</strong></td>
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%; height:30px">DEPARTAMENTO</td>
                            <td style="width: 10%;">'.$dataSolicitud['departamento'].'</td>
                            <td style="width: 25%;">PROVINCIA</td>
                            <td style="width: 10%;"></td>
                            <td style="width: 15%;">DISTRITO</td>
                            <td style="width: 15%;">'.$dataSolicitud['distrito'].'</td>
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table>
                    <tbody>
                        <tr>
                            <td  style="width: 40%;">
                                <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                                    <tbody>
                                        <tr>
                                            <th style="width: 50%;text-align: center;height:50px" rowspan="2"><strong><BR><BR>REPARO</strong></th>
                                            <th style="width: 50%;height:30px">Aplica (Si o No): </th> 
                                        </tr>
                                        <tr>
                                            <th style="text-align: center">NO</th>
                                            <th style="width: 20%;height:30%" style=""></th>
                                        </tr>
                                    
                                    </tbody>
                                </table>
                            </td>
                            <td  style="width: 40%;">
                                <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                                    <tbody>
                                        <tr>
                                            <th style="width: 50%;text-align: center;height:50px" rowspan="2"><strong><BR><BR>PENALIDAD</strong></th>
                                            <th style="width: 50%;height:30px">Aplica (Si o No): </th> 
                                        </tr>
                                        <tr>
                                            <th style="text-align: center">NO</th>
                                            <th style="width: 20%;height:30%" style=""></th>
                                        </tr>
                                    
                                    </tbody>
                                </table>
                            </td>    
                        </tr>
                    </tbody>
                </table>
                
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 40%; height:20px">FECHA TERMINO DE OBRA</td>
                            <td style="width: 20%;">'.date("d/m/Y",  strtotime($dataSolicitud['fechaEjecucion'])).'</td>
                        </tr>
                        <tr>
                            <td style="width: 40%; height:20px">FECHA PREVISTA DE PUESTA EN SERVICIO</td>
                            <td style="width: 20%;">'.date("d/m/Y",  strtotime($dataSolicitud['fechaEjecucion'])).'</td>
                        </tr>  
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="height:20px">1.- La firma de la presente ACTA DE ACEPTACI&Oacute;N es constancia que la obra, suministro o servicio que ampara se ha ejecutado de conformidad con el Proyecto asignado.</td>
                        </tr>
                        <tr>
                            <td style="height:20px">2.- La Recepci&oacute;n definitiva de la obra, suministro o servicio, queda condicionada a la comprobaci&oacute;n y levantamiento de la Hoja de Reparos. As&iacute; como a las normas y penalidades estipuladas en la Orden de Compra generada para este caso.</td>
                        </tr>
                        <tr>
                            <td>3.- Para la aplicaci&oacute;n de PENALIDADES por demoras en la entrega o en el levantamiento de los reparos, se respetar&aacute;n las condiciones pactadas en el Contrato Marco o Contrato Particular de cada obra, suministro o servicio firmado entre las partes. </td>
                        </tr>  
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="height:20px">FIRMA POR TELEF&Oacute;NICA</td>
                        </tr>
                        <tr>
                            <td style="width: 32%;height:70px;"><span><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                            <td style="width: 33%;height:70px;"><span><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                            <td style="width: 35%;height:70px;"><span><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                <tbody>
                    <tr>
                        <td style="height:20px">FIRMA POR EL PROVEEDOR O CONTRATISTA EJECUTOR DE LA OBRA, SUMINISTRO O SERVICIO</td>
                    </tr>
                    <tr>
                        <td style="width: 32%;height:70px;"><span><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                        <td style="width: 33%;height:70px;"><span><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                        <td style="width: 35%;height:70px;"><span><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                    </tr>
                </tbody>
            </table>
                ';
        
        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        $this->logicaGuardarPdf($itemplan, $codigo_solicitud, $dataSolicitud['orden_compra'], $pdf);
        // $pdf->Output('acta_certificacion.pdf', 'I');
        // $pdf->Output('/path/to/file'.$itemplan.'.pdf', 'F');
    }

    function logicaGuardarPdf($itemplan, $codigo_solicitud, $orden_compra, $pdf) {_log("ENTRO33");
        // $pathItemplan = 'uploads/actasOc';
        // if (!is_dir($pathItemplan)) {
        //     mkdir ($pathItemplan, 0777);
        // }
		
		if($itemplan) {
			$nombrePdf = $itemplan.'_'.$orden_compra;
		} else if($codigo_solicitud) {
			$nombrePdf = $codigo_solicitud.'_'.$orden_compra;
		} else if($orden_compra) {
			$nombrePdf = $codigo_solicitud.'_'.$orden_compra;
		}

        $pdf->Output(FCPATH.'uploads/actasOc/acta_'.$nombrePdf.'.pdf', 'F');
    }
    
    function zipActasOcMasivo() {_log("ENTRO44");
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $pathItemplan = 'uploads/actasOc';
            if(is_dir($pathItemplan)) {                
                $fechaActual = $this->m_utils->fechaActual();
                $this->zip->read_dir($pathItemplan,false);
                $fileName = 'actas_fe_'.date("d_m").'.zip';
                $this->zip->archive($pathItemplan.'/'.$fileName);
                
                $data['directorioZip'] =  $pathItemplan.'/'.$fileName;
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
