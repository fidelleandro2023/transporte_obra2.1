<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_solicitud_Vr extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_liquidacion/m_solicitud_Vr');
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
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_VR, ID_PERMISO_HIJO_SOLICITUD_VR);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, ID_PERMISO_HIJO_SOLICITUD_VR, ID_MODULO_PAQUETIZADO);
            $data['title'] = 'SOLICITUD VR';
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_liquidacion/v_solicitud_Vr',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getComboPtr() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $cmbPtr = null;
            $itemplan = $this->input->post('itemplan');

            if($itemplan == null) {
                throw new Exception('ND');
            }
			
			$countPendiente = $this->m_solicitud_Vr->getCountPendienteValidVr($itemplan);

            if($countPendiente > 0) {
                throw new Exception('TIENE SOLICITUD PENDIENTE DE VALIDACI&Oacute;N EN "BANDEJA SOLICITUD VALE RESERVA".');
            }
			
			$flgPaquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);
			
			if($flgPaquetizado == 2 || $flgPaquetizado == 1) {
				$arrayData = $this->m_utils->getPtrByItemplanPqt($itemplan);
			} else {
				$arrayData = $this->m_utils->getPtrByItemplan($itemplan);
			}
			
            $cmbPtr.="<option value=''>Seleccionar Ptr</option>";
            foreach($arrayData as $row) {
                if($row->ptr != null && $row->ptrEstacion != null) {
                    $data['empresacolab'] = $row->empresaColabDesc;
                    $data['jefatura']     = $row->jefatura;
                    $dataAlmCen = explode('|', $row->dataJefaturaEmp);
                    $data['codAlmacen']   = $dataAlmCen[0];
                    $data['codCentro']    = $dataAlmCen[1];
                    $data['idEmpresaColab'] = $dataAlmCen[3];
                    $data['idJefatura']     = $dataAlmCen[2];
                    $data['vr']             = $row->vr;
                    $cmbPtr.= "<option data-id_estacion='".$row->idEstacion."' data-id_subproyecto='".$row->idSubProyecto."' value='".$row->ptr."_".$row->est_innova."'>$row->ptrEstacion</option>";
                }
            }
            $data['error'] = EXIT_SUCCESS;
            $data['cmbPtr'] = $cmbPtr;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getVr() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $ptr_estado  = $this->input->post('ptr');
            $itemplan    = $this->input->post('itemplan');
            $idEstacion  = $this->input->post('idEstacion');

            $ptr = explode('_', $ptr_estado);
            if($ptr == null || $ptr == '') {
                throw new Exception('ptr no registrado');
            }

            if($itemplan == null || $itemplan == '') {
                throw new Exception('itemplan null');
            }

            if($idEstacion == null || $idEstacion == '') {
                throw new Exception('idEstacion null');
            }
            $vr = $this->m_utils->getVrByPtr($ptr[0]);
            
            $data['error'] = EXIT_SUCCESS;
            $data['vr']    = $vr;
            $dataMaterial = $this->m_utils->getMaterialByPo($ptr[0], $itemplan, $idEstacion);
            $anio = explode('-', $ptr[0]);
            if($anio[0] != 2018) {
                $data['tablaKit'] = $this->tablakit($dataMaterial, $itemplan, $idEstacion);
            } else {
                $data['tablaKit'] = null;
            }
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertSolicitudKit() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $arrayDataKitInsert = $this->input->post('arrayDataKitInsert');

            $arrayNew = array();
            $codigo = $this->m_solicitud_Vr->getCodigoSolicitudVr();

            if($codigo == null || $codigo == '') {
                throw new Exception('Error codigo vr comunicarse con el programador');
            }

            $arrayPlanObraPoCantFin = array();
            
            foreach($arrayDataKitInsert as $row) {
                unset($row['costoMaterial']);
                $row['fecha_registro'] = $this->fechaActual();
                $row['idUsuario']      = $this->session->userdata('idPersonaSession');
                $row['codigo']         = $codigo;
                array_push($arrayNew, $row);

                $dataPoCantFin['codigo_po']       = $row['ptr'];
                $dataPoCantFin['codigo_material'] = $row['material'];
                $dataPoCantFin['cantidad_final']  = $row['cantidadFin'];
                array_push($arrayPlanObraPoCantFin, $dataPoCantFin);
            }
            
            $data = $this->m_solicitud_Vr->insertSolicitudKit($arrayNew, $arrayPlanObraPoCantFin, $codigo);
            // $dataMaterial = $this->m_utils->getMaterialByPo($ptr[0], $itemplan);
            // if(count($dataMaterial) > 0) {
            //     $data['tablaKit'] = $this->tablakit($dataMaterial);
            // } else {
            //     $data['tablaKit'] = null;
            // }
            $data['codigoSolicitud'] = $codigo;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablakit($dataMaterial, $itemplan, $idEstacion) {
        $html = '<div class="form-group">
                    <button class="btn btn-success" onclick="getKitMaterial();">nuevo material</button>
                </div>
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>C&Oacute;DIGO</th>
                            <th>MATERIAL</th>
                            <th>COSTO MATERIAL</th>  
                            <th>CANTIDAD PARA OBRA</th>
                            <th>CANTIDAD SOBRANTE</th>
                            <th>TIPO</th> 
                            <th>CANTIDAD</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
		$idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
        $flg_valid_estadoPlan = 0;
        $flg_valid_porcentaje = 0;
		
		$countExpedienteActivo = $this->m_utils->getCounExpedienteItemplanByItem($itemplan, 'ACTIVO');
		
		
		if($countExpedienteActivo == 0) {
			$flg_valid_estadoPlan = 0;
			$flg_valid_porcentaje = 0;
		} else {
			if($idEstadoPlan != 3) {
				$flg_valid_estadoPlan = 1;
			}
			
			$countPorcentaje = $this->m_utils->getPorcentajeByItemplanAndEstacion($itemplan, $idEstacion);
			if($countPorcentaje > 0) {
				$flg_valid_porcentaje = 1;
				//throw new Exception('La estaci&oacute;n no debe estar liquidada (100%), para generar una solicitud de vr.');
			}
		}
		
        foreach($dataMaterial as $row){
            $cont++;
            // $checked  = ($row['kitIdMaterial'] == 1) ? 'checked' : null;
            // $disabled = ($row['kitIdMaterial'] == 1) ? 'disabled' : null;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['id_material'].'</td>
                            <td style="background:'.$style.'">'.$row['descrip_material'].'</td>
                            <td style="background:'.$style.'">'.$row['costo_material'].'</td>
                      
                            <td style="background:'.$style.'"><input class="form-control" id="cantidadObra_'.$cont.'" type="text" value="'.$row['cantidad_ingreso'].'" disabled/></td>								
                            <td style="background:'.$style.'"><input class="form-control" id="cantidadIngreso_'.$cont.'" type="text" disabled/></td>								                            
                            <td>'.__buildComboTipoSolicitud('cmbTipoSolicitud_'.$cont, 'getDataInsert('.$row['id_material'].','.$cont.','.$row['costo_material'].','.$row['costo_total'].','.$flg_valid_estadoPlan.','.$flg_valid_porcentaje.','.$row['flg_solicitud'].')', $row['flg_solicitud'], 1).'</td>
                            <td><input id="inputCantidad_'.$cont.'" type="text" class="form-control" onchange="getDataInsert('.$row['id_material'].','.$cont.','.$row['costo_material'].','.$row['costo_total'].','.$flg_valid_estadoPlan.','.$flg_valid_porcentaje.','.$row['flg_solicitud'].');"></td>                                            		                        
                        </tr>';
        }
            $html .='</tbody>
                </table>';
            return utf8_decode($html);
    }

    function insertSap() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $file     = $_FILES ["file"] ["name"];
            $filetype = $_FILES ["file"] ["type"];
            $filesize = $_FILES ["file"] ["size"];
            $tmp      = $_FILES ["file"] ["tmp_name"];
    
            $itemplan       = $this->input->post('itemplan');
            $ptr            = $this->input->post('ptr');
            $idJefaturaSap  = $this->input->post('idJefaturaSap');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $vr             = $this->input->post('valeReserva');
            $ptr_estado     = $this->input->post('ptr_estado');
            
            $estado = explode('_', $ptr_estado);
            
            if($itemplan == null || $ptr == null || $idJefaturaSap == null || $idEmpresaColab == null || $vr == null || $estado[1] == null) {
                throw new Exception('ND');
            }

            $file2 = utf8_decode($file);
            $ubicacion     = 'uploads/detalle_solicitud_vr/';
    
            if (move_uploaded_file($_FILES['file']['tmp_name'], $ubicacion."/".$file2)) {
              
            } else {
                throw new Exception('ND');
            }
    
            $handle = fopen($ubicacion."/".$file2, "r");
            $linea = fgets($handle);  
            $arrayData = array();
            $comp = preg_split("/[\t]/", $linea);
            $count = 0;
            $codigo = $this->m_solicitud_Vr->getCodigoSolicitudVr();
            while($line = fgets($handle)) {
                $count++; 
                $comp = preg_split("/[\t]/", $line);
                $countColumn = count($comp);
                
                if($comp[1] == '' || $comp[1] == null) {
                    throw new Exception('El formato esta subiendo filas en blanco');
                }
                
                if($comp[0] != 1 && $comp[0] != 2 && $comp[0] != 4) {
                    throw new Exception('La columna "tipo de solicitud" no tiene el formato correcto');
                }
                // if($comp[0] == null || $comp[1] == null || $comp[3] == null || $comp[4] == null || $comp[5] == null) {
                //     throw new Exception('Tiene campos vacÃ­os en su formato');
                // }

                // if(trim($comp[5]) != trim($vr)) {
                //     throw new Exception('El vale de reserva no coincide con el que presenta en el documento');
                // }
                //$codigo = $codigo+$count;
                $comp[5] = (!isset($comp[5])) ? null : $comp[5];    
                $json = array(
                    'itemplan'           => $itemplan,
                    'ptr'                => $estado[0],
                    'idJefaturaSap'      => $idJefaturaSap,
                    'idEmpresaColab'     => $idEmpresaColab,
                    'flg_tipo_solicitud' => $comp[0],
                    'material'           => $comp[1],
                    'textoBreve'         => $comp[2],
                    'cantidadInicio'     => $comp[3],
                    'cantidadFin'        => $comp[4],
                    'vr'                 => $comp[5],
                    'codigo'             => $codigo,  
                    'fecha_registro'     => $this->fechaActual(),
                    'idUsuario'          => $this->session->userdata('idPersonaSession')
                );
                array_push($arrayData, $json);    
            }
            
            list($tabla, $arrayStyle) = $this->tablaBlocNotas($arrayData, $vr, $countColumn);

            $data['tablaBloc'] = $tabla;

            foreach($arrayStyle as $row2) {
                if($row2 != null || $row2 != '') {
                    throw new Exception('Error al subir el documento, formato incorrecto');
                }
            }
                if($comp[0] == FLG_TIPO_SOLICITUD_DEVOLUCION) {
                    if(trim($estado[1]) != '05' && trim($estado[1]) != '052' && trim($estado[1]) != '004' && trim($estado[1]) != '041' && trim($estado[1]) != '04') {
                        throw new Exception('Error al subir el documento, la devoluci&oacute;n solo se permite por ptr en estado 05, 052 o 004');
                    }
                }
            $arrayUpdate = array('vale_reserva' => $vr);
            $data = $this->m_solicitud_Vr->insertSolicitud($arrayData, $arrayUpdate, $itemplan, $ptr);

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('ND');
            }

            fclose($handle);
            $data['codigo'] = $codigo;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage(); 
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaBlocNotas($json, $vr, $countColumn) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>TIPO DE SOLICITUD</th>
                            <th>MATERIAL</th>
                            <th>TEXTO BREVE DE MATERIAL</th>                            
                            <th>CANT. DICE</th>
                            <th>DEBE DECIR</th>
                            <th>VR</th>                  
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();

        foreach($json as $row){
            $arrayTipoSolicitud = array(FLG_TIPO_SOLICITUD_ADICIONAR, FLG_TIPO_SOLICITUD_ANULAR, 
                                        FLG_TIPO_SOLICITUD_DEVOLUCION, FLG_TIPO_SOLICITUD_MODIFICAR);
                                        
            if(trim($row['flg_tipo_solicitud']) == 0) {
                $style = '#33FFBE';
            }   
            else if($countColumn != 6) {
                $style = '#FFE033';
            }
            else if(intval(trim($row['vr'])) != intval(trim($vr))) {
                $style = '#8CE857';
                //throw new Exception('El vale de reserva no coincide con el que presenta en el documento');
            }

            else if($row['flg_tipo_solicitud'] == null || $row['flg_tipo_solicitud'] == 0 || $row['material'] == null || $row['textoBreve'] == null || $row['cantidadInicio'] == null || $row['cantidadFin'] == null) {
                $style = '#F6A5A5';
            }
            else if(!in_array(trim($row['flg_tipo_solicitud']), $arrayTipoSolicitud) && !is_int($row['flg_tipo_solicitud'])) {
                $style = '#33FFBE';
            } else {
                $style = null;
            }
            array_push($arrayStyle, $style);
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$row['flg_tipo_solicitud'].'</td>
                            <td style="background:'.$style.'">'.$row['material'].'</td>
                            <td style="background:'.$style.'">'.$row['textoBreve'].'</td>							
                            <th style="background:'.$style.'">'.$row['cantidadInicio'].'</th>
                            <th style="background:'.$style.'">'.$row['cantidadFin'].'</th>		
                            <th style="background:'.$style.'">'.$row['vr'].'</th>					                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return array(utf8_decode($html), $arrayStyle);
    }

    function getKitMaterial() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $po         = $this->input->post('po');
            $idEstacion = $this->input->post('idEstacion');
            $itemplan   = $this->input->post('itemplan');

            if($po == null || $po == '') {
                throw new Exception('po null, comunicarse con el programador');
            }

            if($idEstacion == null || $idEstacion == '') {
                throw new Exception('estacion null, comunicarse con el programador');
            }

            if($itemplan == null || $itemplan == '') {
                throw new Exception('itemplan null, comunicarse con el programador');
            }
            $arrayDataKit = $this->m_solicitud_Vr->getKitMaterialSolicitud($itemplan, $po, $idEstacion);
            $tablaKit = $this->tablaKitByItemplan($arrayDataKit);

            $data['error'] = EXIT_SUCCESS;
            $data['tablaKitItemplan'] = $tablaKit;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaKitByItemplan($arrayData) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>C&Oacute;DIGO</th>
                            <th>MATERIAL</th>   
                            <th>SELECCIONAR</th>     
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($arrayData as $row){
            $cont++;
            // $checked  = ($row['kitIdMaterial'] == 1) ? 'checked' : null;
            // $disabled = ($row['kitIdMaterial'] == 1) ? 'disabled' : null;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['id_material'].'</td>
                            <td style="background:'.$style.'">'.$row['descrip_material'].'</td>
                            <td><input id="checkit_'.$cont.'" type="checkbox" onchange="getDataKit('.$row['id_material'].','.$cont.');"></td>                                            		                        
                        </tr>';
        }
            $html .='</tbody>
                </table>';
            return utf8_decode($html);
    }

    function insertKitMaterialSolicitud() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $arraySolicitudMat = $this->input->post('arrayKitSelec');
            $itemplan          = $this->input->post('itemplan');
            $po                = $this->input->post('po');
            $idEstacion        = $this->input->post('idEstacion');

            $data = $this->m_solicitud_Vr->updateFlgSolicitud($arraySolicitudMat);
            $dataMaterial = $this->m_utils->getMaterialByPo($po, $itemplan, $idEstacion);

            $data['tablaKit'] = $this->tablakit($dataMaterial, $itemplan, $idEstacion);
            
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
	
	function getDataUpdateRobotDevolucion() {
        $id    = null;
        $grafo = null;
        $pep1  = null;
        $pep2  = null;
        
        $dataArraySolicitud = $this->m_solicitud_Vr->getDataUpdateRobotDevolucion();
        
        foreach($dataArraySolicitud as $row) {
			$send_rpa = 1;//COMO SON DEVOLUCIONES POR DEFECTO 1
            $idProyecto = $this->m_utils->getProyectoByItemplan($row['itemplan']);
            $countPepBianual = $this->m_utils->getCountPepBianualByItemplan($row['itemplan']);
        
            if($countPepBianual == 0) { //SI TIENE TOTAL DE DEVOLUCION Y NO ES BIANUAL SE GENERA UNA NUEVA PEP
                $costoTotalDev = $this->m_solicitud_Vr->getSumTotalSolicitud($row['codigo']);
                if($idProyecto == 3) {
                    $dataArrayPlanObra = $this->m_utils->getPlanobraByItemplan($row['itemplan']);
                    $dataArrayPep = $this->m_solicitud_Vr->getPepGrafoByMatPtrSisegos($dataArrayPlanObra['indicador'], $costoTotalDev);
                } else {
                    $dataArrayPep = $this->m_solicitud_Vr->getPepGrafoByMatPtr($row['ptr'], $costoTotalDev);
                }
                $dataRowPep = explode('|', $dataArrayPep);
                $resp  = $dataRowPep[0];
                $id    = $dataRowPep[1];
                $grafo = $dataRowPep[2];
                $pep1  = $dataRowPep[3];
                $pep2  = $dataRowPep[4];
                
                //2 = SIN PRESUPUESTO, 3 = SIN GRAFO, 4 = SIN CONFIGURACION,5 = PEP NO VINO EN SAP

                if($resp == 2) {
					$send_rpa = null;
                    $pep1 = 'PEP SIN PRESUPUESTO';
                } else if($resp == 3) {
					$send_rpa = null;
                    $pep1 = 'PEP SIN GRAFO';
                } else if($resp == 4) {
					$send_rpa = null;
                    $pep1 = 'PEP SIN CONFIGURACION';
                } else if($resp == 5) {
					$send_rpa = null;
                    $pep1 = 'PEP NO VINO EN SAP';
                }
                
            } else {
                $dataArrayPo = $this->m_utils->getPlanObraPoByCodigoPo($row['ptr']);
                $pep1  = $dataArrayPo['pep1'];
                $pep2  = $dataArrayPo['pep2'];
                // if($idProyecto == 3) {
                    $grafo = $dataArrayPo['grafo'];
                // } else {
                //     $arrayPep = $this->m_utils->getPep2GrafoDataByPep($pep2);

                //     if($arrayPep) {
                //         $data = $this->m_utils->updateEstadoPep2Grafo($pep2, $arrayPep['grafo']);
                //         if($data['error'] == EXIT_SUCCESS) {
                //             $grafo = $arrayPep['grafo'];
                //         }
                //     }
                // }
            }
            
            $arrayDataUpdate = array('pep1'     => $pep1,
                                     'pep2'     => $pep2,
                                     'grafo'    => $grafo,
                                     'send_rpa' => $send_rpa);
            $data = $this->m_solicitud_Vr->updateSolitudVrPep($row['codigo'], $arrayDataUpdate);
        }      
    }
}