<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_gestion_obra_publica extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_gestion_obra_publica');
        $this->load->model('mf_tranferencias/m_tranferencia_wu');        
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){    	          
	        
               $item = (isset($_GET['item']) ? $_GET['item'] : '');               
               $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($item);
               if($infoItemplan != null){//SI HAY DATOS
                   $data['hasInfo']                 =   1;
                   $data['infoItem']                =   $infoItemplan;
                   $data['tablaCotizacion']         =   $this->makeHTLMTablaCotizacion($infoItemplan);
                   $data['tablaRespuCotizacion']    =   $this->makeHTLMTablaRespuCotizacion($infoItemplan);
                   $data['tablaDatosConvenio']      =   $this->makeHTLMTablaDatosConvenio($infoItemplan);                  
                   $data['tablaResumenEvaEcono']    =   $this->makeHTLMTablaEvaluacionEconomica($infoItemplan);
                   $data['hastCartCotizacion']      =   (($infoItemplan['numero_carta_cotizacion'] != null) ? 1 : 0);
                   $data['hastCartRespuesta']       =   ((( $infoItemplan['convenio_carta_respuesta'] == 'SI' && 
                                                            $infoItemplan['estado_carta_respuesta'] == 'SUSCRITO') 
                                                        ||  $infoItemplan['convenio_carta_respuesta'] == 'NO') ? 1 : 0);
                   $data['hastNumFactura']          =   (($infoItemplan['num_factura_convenio'] != null) ? 1 : 0);
                   $data['tablaKickOff']            =   $this->makeHTLMTablaKickOff($infoItemplan);
                   $data['estadoKO']                =   $infoItemplan['estado_kickoff'];                   
                   $data['has_ko']                  =   $infoItemplan['has_kickoff'];
                   
               }else{
                   $data['hasInfo'] =   2;
               }              
	           
	           $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);
        	   
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_plan_obra/v_gestion_obra_publica',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function makeHTLMTablaCotizacion($infoItemplan){
        $html = '<table id="tablaCotizacion" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NUMERO CARTA</th>
                            <th>FECHA ENVIO</th>                            
                            <th>USUARIO ENVIO</th>
                            <th>PDF</th>                                                  
                        </tr>
                    </thead>                    
                    <tbody>';
				if($infoItemplan!=null){
				    $listaDatos = $this->m_gestion_obra_publica->getCotizacionesLog($infoItemplan['itemplan']);
				    foreach($listaDatos->result() as $row){
                    $html .=' <tr>
    							<td>'.$row->numero_carta_cotizacion.'</td>
    							<td>'.$row->fecha_carta_cotizacion.'</td>							
    							<td>'.$row->usua_envio_ct.'</td>
    							<td><a href="'.utf8_encode($row->ruta_carta_cotizacion_pdf).'" target="_blank"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/pdf.png"></a></td>                           
    						</tr>';
				    }
				}
			 $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }
    
    function saveCartaCotizacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
            if($logedUser != null){
                $numCartaRespu  = $this->input->post('inputNumCartaCoti');
                $itemplan       = $this->input->post('item');
                $uploaddir =  'uploads/obra_publica/'.$itemplan.'/';//ruta final del file
                $uploadfile = $uploaddir . basename($_FILES['fileCR']['name']);
                if (! is_dir ( $uploaddir))
                    mkdir ( $uploaddir, 0777 );
                
                if (move_uploaded_file($_FILES['fileCR']['tmp_name'], $uploadfile)) {
                    $dataCR = array (
            	            "ruta_carta_cotizacion_pdf" =>  $uploadfile,
            	            "fecha_carta_cotizacion"    =>  $this->fechaActual(),
            	            "usuario_carta_cotizacion"  =>  $this->session->userdata('idPersonaSession'),
            	            "numero_carta_cotizacion"   =>  $numCartaRespu
            	        );
                   $data    =   $this->m_gestion_obra_publica->saveFileCartaRespuesta($itemplan, $dataCR);
                   if($data['error']==EXIT_SUCCESS){
                       $dataCR['itemplan']  =  $itemplan;
                       $data    =   $this->m_gestion_obra_publica->saveLogCotizacion($dataCR);
                       if($data['error']==EXIT_SUCCESS){              
                           $hasParaliza = $this->m_gestion_obra_publica->hasParalizaCotizacion($itemplan);
                           if($hasParaliza  ==  0){
                               $dataParalizacion = array (
                                   "itemplan" =>  $itemplan,
                                   "flg_activo"    =>  1,
                                   "idUsuario"  =>  $this->session->userdata('idPersonaSession'),
                                   "fechaRegistro"   =>  $this->fechaActual(),
                                   "idMotivo"   =>  23,//pendiente de aprobacion de cotizacion adicional
                                   "comentario"   =>  'PENDIENTE DE FACTURA',
                                   "flgEstado"   =>  1
                               );
                               $data = $this->m_gestion_obra_publica->insertParalizacionCoti($dataParalizacion);
                               if($data['error']==EXIT_SUCCESS){
                                   $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                                   $data['tablaCotizacion'] =   $this->makeHTLMTablaCotizacion($infoItemplan);
                                   $data['cartaCoti'] =  $infoItemplan['numero_carta_cotizacion'];
                               }else{
                                   throw new Exception('Hubo un problema al paralizar la obra.');
                               }
                           }else{
                               $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                               $data['tablaCotizacion'] =   $this->makeHTLMTablaCotizacion($infoItemplan);
                               $data['cartaCoti'] =  $infoItemplan['numero_carta_cotizacion'];
                           }
                       }else{
                           throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');
                       }
                   }else{
                       throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');                    
                   }
                }else {
                    throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                }
            }else{
                throw new Exception('Su sesion ha expirado, por favor vuelva a iniciar sesion.');               
            } 
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
    
    public function makeHTLMTablaRespuCotizacion($infoItemplan){
        $html = '<table id="tablaRespuCotizacion" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NUMERO CARTA COTIZACION</th>
                            <th>NUMERO CARTA RESPUESTA</th>
                            <th>FECHA ENVIO</th>
                            <th>USUARIO ENVIO</th>
                            <th>CONVENIO</th>
                            <th>ESTADO CONVENIO</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($infoItemplan!=null){
            $listaDatos = $this->m_gestion_obra_publica->getRespuestaLog($infoItemplan['itemplan']);
            foreach($listaDatos->result() as $row){
                $html .=' <tr>  
                            <td>'.$row->numero_carta_cotiza_respu.'</td>
							<td>'.$row->numero_carta_respuesta.'</td>
							<td>'.$row->fecha_carta_respuesta.'</td>
							<td>'.$row->usua_envio_cr.'</td>
						    <td>'.$row->convenio_carta_respuesta.'</td>
					        <td>'.$row->estado_carta_respuesta.'</td>
							<td><a href="'.utf8_encode($row->ruta_carta_respuesta_pdf).'" target="_blank"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/pdf.png"></a></td>
						  </tr>';
            }
        }
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }
    
    function saveCartaRespuesta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
            if($logedUser != null){
                $numCartaRespu  = $this->input->post('inputNumCartaRespu');
                $convenio       = $this->input->post('selectConvenio');
                $estado         = $this->input->post('selectEstadoGes');
                $itemplan       = $this->input->post('item');
                $numCartaCoti   = $this->input->post('numCarRe');
        
                $uploaddir =  'uploads/obra_publica/'.$itemplan.'/';//ruta final del file
                $uploadfile = $uploaddir . basename($_FILES['fileCR2']['name']);
                if (! is_dir ( $uploaddir))
                    mkdir ( $uploaddir, 0777 );
        
                if (move_uploaded_file($_FILES['fileCR2']['tmp_name'], $uploadfile)) {
                    $dataCR = array (
                        "ruta_carta_respuesta_pdf" =>  $uploadfile,
                        "fecha_carta_respuesta"    =>  $this->fechaActual(),
                        "usuario_carta_respuesta"  =>  $this->session->userdata('idPersonaSession'),
                        "numero_carta_respuesta"   =>  $numCartaRespu,
                        "convenio_carta_respuesta" =>  $convenio,
                        "estado_carta_respuesta"   =>  $estado,
                        "numero_carta_cotiza_respu"=>  $numCartaCoti
                    );
                    $data    =   $this->m_gestion_obra_publica->saveFileCartaRespuesta($itemplan, $dataCR);
                    if($data['error']==EXIT_SUCCESS){
                        $dataCR['itemplan']  =  $itemplan;
                        $data    =   $this->m_gestion_obra_publica->saveLogRespuCotizacion($dataCR);
                        if($data['error']==EXIT_SUCCESS){
                            $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                            $data['tablaRespuCotizacion'] =   $this->makeHTLMTablaRespuCotizacion($infoItemplan);
                            $data['cartaCoti'] =  $infoItemplan['numero_carta_cotizacion'];
                        }else{
                            throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');
                        }
                    }else{
                        throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');
                    }
                }else {
                    throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                }
            }else{
                throw new Exception('Su sesion ha expirado, por favor vuelva a iniciar sesion.');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaDatosConvenio($infoItemplan){
        $html = '<table id="tablaDatosConvenio" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NUMERO FACTURA</th>
                            <th>ESTADO FACTURA</th>
                            <th>OP GRAVADA SIN IGV</th>
                            <th>OP GRAVADA RED SIN IGV</th>
                            <th>COSTO DE LA OBRA</th>
                            <th>IGV</th>
                            <th>IMPORTE TOTAL</th>
                            <th>DEPOSITO</th>
                            <th>DETRACCION U OTROS</th>
                            <th>USUARIO REG.</th>
                            <th>FEC. REG CONVENIO</th>                            
                        </tr>
                    </thead>
                    <tbody>';
        if($infoItemplan!=null){
            $html .=' <tr>
						<td>'.$infoItemplan['num_factura_convenio'].'</td>
						<td>'.$infoItemplan['estado_factura_convenio'].'</td>
						<td>'.number_format($infoItemplan['op_sin_igv_convenio'], 2, '.', ',').'</td>
					    <td>'.number_format($infoItemplan['op_red_sin_igv_convenio'], 2, '.', ',').'</td>
				        <td>'.number_format($infoItemplan['costo_obra_convenio'], 2, '.', ',').'</td>
				        <td>'.number_format($infoItemplan['igv_convenio'], 2, '.', ',').'</td>
			            <td>'.number_format($infoItemplan['total_convenio'], 2, '.', ',').'</td>
				        <td>'.$infoItemplan['deposito_convenio'].'</td>
						<td>'.$infoItemplan['detraccion_convenio'].'</td>
					    <td>'.$infoItemplan['usua_convenio'].'</td>
						<td>'.$infoItemplan['fec_reg_convenio'].'</td>
					</tr>';
        }
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }    
    
    function saveDatosConvenio(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
            if($logedUser != null){
                $numFactura     = $this->input->post('inputFactura');
                $estadoFactura  = $this->input->post('selectEstaFactu');
                $opSinIgv       = $this->input->post('opGraSinIGV');
                $opRedSinIgv    = $this->input->post('opRedSinIGV');
                $costoObra      = $this->input->post('costoObra');
                $igvConve       = $this->input->post('igvObra');
                $total          = $this->input->post('importeTotal');
                $hasDeposito    = $this->input->post('selectDeposito');
                $hasDetracc     = $this->input->post('selectDetrac');
                $itemplan       = $this->input->post('item');
                
                    $dataCR = array (
                        "num_factura_convenio"      =>  $numFactura,
                        "estado_factura_convenio"   =>  $estadoFactura,
                        "op_sin_igv_convenio"       =>  $opSinIgv,
                        "op_red_sin_igv_convenio"   =>  $opRedSinIgv,
                        "costo_obra_convenio"       =>  $costoObra,
                        "igv_convenio"              =>  $igvConve,
                        "total_convenio"            =>  $total,
                        "deposito_convenio"         =>  $hasDeposito,
                        "detraccion_convenio"       =>  $hasDetracc,
                        "usuario_reg_convenio"      =>  $this->session->userdata('idPersonaSession'),
                        "fec_reg_convenio"          =>  $this->fechaActual()
                    );
                    $data    =   $this->m_gestion_obra_publica->saveFileCartaRespuesta($itemplan, $dataCR);
                    if($data['error']==EXIT_SUCCESS){
                        if($hasDeposito ==   'SI'    &&  $hasDetracc ==   'SI'){
                            $hasParaliza = $this->m_gestion_obra_publica->hasParalizaCotizacion($itemplan);
                            if($hasParaliza  !=  0){
                                $dataRP = array (
                                    "fechaReactivacion"   =>  $this->fechaActual(),
                                    "flg_activo"          =>  0
                                );
                                $data   =   $this->m_gestion_obra_publica->updateParalizacionCoti($dataRP, $itemplan);
                                if($data['error']==EXIT_SUCCESS){
                                    $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                                    $data['tablaDatosConvenio'] =   $this->makeHTLMTablaDatosConvenio($infoItemplan);
                                }else{
                                    throw new Exception('Hubo un problema con la reactivacion del itemplan paralizado.');
                                }
                            }else{
                                $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                                $data['tablaDatosConvenio'] =   $this->makeHTLMTablaDatosConvenio($infoItemplan);
                            }
                        }else{
                            $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                            $data['tablaDatosConvenio'] =   $this->makeHTLMTablaDatosConvenio($infoItemplan);
                        }                        
                    }else{
                        throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');
                    }           
            }else{
                throw new Exception('Su sesion ha expirado, por favor vuelva a iniciar sesion.');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    public function makeHTLMTablaEvaluacionEconomica($infoItemplan){
        
        $estilo = '';
        $total_mat = 0;
        $total_mo = 0;
        $costo_obra = $infoItemplan['costo_obra_convenio'];
        
        $listaPtrs  =   $this->m_gestion_obra_publica->getPtrsAprobadasByItemplan($infoItemplan['itemplan']);        
        
        $html2 = '<table id="tablaPtrs" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>CODIGO PO</th>
                            <th>ESTACION</th>
                            <th>AREA</th>
                            <th>ESTADO</th>
                            <th>TIPO</th>
                            <th>COSTO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($listaPtrs->result() as $row){
            $html2 .=' <tr>
    							<td>'.$row->itemplan.'</td>
							    <td>'.$row->codigo_po.'</td>
							    <td>'.$row->estacionDesc.'</td>
						        <td>'.$row->areaDesc.'</td>    							
						        <td>'.$row->estado.'</td>
					            <td>'.$row->tipoArea.'</td>
							    <td>'.number_format($row->costo_total, 2, '.', ',').'</td>
    						</tr>';
            
            $total_mat = $total_mat + $row->costo_total;
        }
        $html2 .='</tbody>
                </table>';
        
        $html = '<table id="tablaResumenEvaEconomica" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>COSTO TOTAL MAT</th>
                            <th>COSTO TOTAL MO</th>
                            <th>COSTO TOTAL DE LA OBRA</th>
                            <th>SALDO</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($infoItemplan!=null){
            $saldo = ($costo_obra-($total_mat+$total_mo));
			if($costo_obra > 0){
            $porcentaje = number_format((($saldo*100)/$costo_obra), 2, '.', ',');
			}else{
				$porcentaje = 0;
			}
            if($porcentaje  >=  85){//verde
               $estilo  = "style='color: white; background: #5ec501'";
            }else if($porcentaje >= 70){//amarillo
                $estilo  = "style='color: white; background: #dccd08'";
            }else{//rojo
                $estilo  = "style='color: white; background: red'";
            }          
            $html .=' <tr>
    							<td>'.number_format($total_mat, 2, '.', ',').'</td>
    							<td>'.number_format($total_mo, 2, '.', ',').'</td>    							
							    <td>'.number_format($costo_obra, 2, '.', ',').'</td>	
						        <td '.$estilo.'>'.number_format($saldo, 2, '.', ',').'</td>					        
    						</tr>';
        }
        $html .='</tbody>
                </table>';
        return utf8_decode($html.'<br>'.$html2);
    }
    
    public function makeHTLMTablaPtrs($listaPtrs){
    
        $html = '<table id="tablaPtrs" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>CODIGO PO</th>
                            <th>ESTADO</th>
                            <th>COSTO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($listaPtrs->result() as $row){
            $html .=' <tr>
    							<td>'.$row->itemplan.'</td>
    							<td>'.$row->codigo_po.'</td>							    
						        <td>'.$row->estado.'</td>
							    <td>'.number_format($row->costo_total, 2, '.', ',').'</td>
    						</tr>';
        }
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }
    
    public function makeHTLMTablaKickOff($infoItemplan){
        $html = '<table id="tablaKickOff" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ESTADO</th>                            
                            <th>USUARIO EJECUTA</th>
                            <th>FECHA EJECUTA</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($infoItemplan!=null){
            $html .=' <tr>
						<td>'.$infoItemplan['estado_kickoff'].'</td>    							
						<td>'.$infoItemplan['usuario_kickoff'].'</td>
					    <td>'.$infoItemplan['fecha_ejecuta_kickoff'].'</td>
						<td><a href="'.utf8_encode($infoItemplan['ruta_pdf_kick_off']).'" target="_blank"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/pdf.png"></a></td>
					</tr>';
        }
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
    }
    
    function ejecutarKickOff(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
            if($logedUser != null){
                $itemplan  = $this->input->post('item');
           
                $uploaddir =  'uploads/obra_publica/'.$itemplan.'/';//ruta final del file
                $uploadfile = $uploaddir . basename($_FILES['fileKO']['name']);
                if (! is_dir ( $uploaddir))
                    mkdir ( $uploaddir, 0777 );
        
                if (move_uploaded_file($_FILES['fileKO']['tmp_name'], $uploadfile)) {
                    $dataCR = array (
                        "ruta_pdf_kick_off"         =>  $uploadfile,
                        "fecha_ejecuta_kickoff"     =>  $this->fechaActual(),
                        "usuario_ejecuta_kickoff"   =>  $this->session->userdata('idPersonaSession'),
                        "estado_kickoff"            =>  'EJECUTADO'
                    );
                    $data    =   $this->m_gestion_obra_publica->saveFileCartaRespuesta($itemplan, $dataCR);
                    if($data['error']==EXIT_SUCCESS){                
                        $fec1 = date('Y-m-d');
                        $fec2 = strtotime ( '+1 day' , strtotime ($fec1) );
                        $fechaInicio   = date ( 'Y-m-j' , $fec2 );
                        $subproy = $this->m_utils->getInfoItemplan($itemplan)['idSubProyecto'];    
                        $fechaPrevEjecu = $this->m_utils->getCalculoTiempoSubproyecto($fechaInicio,$subproy);
                        $arrayDataPlan = array('fechaInicio'    =>  $fechaInicio,
                                                'fechaPrevEjec' =>  $fechaPrevEjecu);
                        $data   =   $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataPlan);
                        if($data['error']==EXIT_SUCCESS){
                            $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                            $data['tablaKickOff']   =   $this->makeHTLMTablaKickOff($infoItemplan);                    
                        }else{
                            throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');
                        }
                    }else{
                        throw new Exception('Hubo un problema con el registro de la Carta Respeusta.');
                    }
                }else {
                    throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                }
            }else{
                throw new Exception('Su sesion ha expirado, por favor vuelva a iniciar sesion.');
            }
        }catch(Exception $e){
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
    
    function saveCartaBasica(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
            if($logedUser != null){
                $itemplan       = $this->input->post('item');
                $uploaddir =  'uploads/obra_publica/'.$itemplan.'/';//ruta final del file
                $uploadfile = $uploaddir . basename($_FILES['fileCB']['name']);
                if (! is_dir ( $uploaddir))
                    mkdir ( $uploaddir, 0777 );
    
                if (move_uploaded_file($_FILES['fileCB']['tmp_name'], $uploadfile)) {
                    $dataCR = array (
                        "ruta_carta_pdf" =>  $uploadfile,
                        //"fecha_carta_"    =>  $this->fechaActual(),
                        "usuario_envio_carta"  =>  $this->session->userdata('idPersonaSession')
                    );
                    $data    =   $this->m_gestion_obra_publica->saveFileCartaRespuesta($itemplan, $dataCR);
                    if($data['error']==EXIT_SUCCESS){
                        $infoItemplan    =   $this->m_gestion_obra_publica->getInfoItemplan($itemplan);
                        $data['newPathCarta'] =  $infoItemplan['ruta_carta_pdf'];                        
                    }else{
                        throw new Exception('Hubo un problema con el registro de la Carta.');
                    }
                }else {
                    throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                }
            }else{
                throw new Exception('Su sesion ha expirado, por favor vuelva a iniciar sesion.');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}