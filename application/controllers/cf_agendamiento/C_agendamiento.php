<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_agendamiento extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_agendamiento/m_agendamiento');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	
            $data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_AGENDAMIENTO, ID_PERMISO_HIJO_AGENDAR);
            //$data['title'] = 'MATRIZ AGENDAMIENTO';
            // $data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            //$data['tablaMatriz'] = $this->tablaMatriz();
            $data['opciones'] = $result['html'];
            $this->load->view('vf_agendamiento/v_agendamiento',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getDataFormulario() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try{
            $itemplan = $this->input->post('itemplan');
            $fechaAgendamiento = $this->input->post('fechaAgendamiento');

            if($itemplan == null) {
                throw new Exception('ND');
            }
            $arrayEstado = array(ID_ESTADO_PLAN_EN_OBRA);
            $arrayData = $this->m_agendamiento->getBandaHorariaByItemplan($itemplan, $fechaAgendamiento, $arrayEstado);
            foreach($arrayData as $row) {
                $data['empresacolab']    = $row->empresaColabDesc;
                $data['jefatura']        = $row->jefatura;
                $data['idEmpresaColab']  = $row->idEmpresaColab;

                $data['subProyectoDesc'] = $row->subProyectoDesc;
                $data['nombreProyecto']  = $row->nombreProyecto;
                $data['estadoPlanDesc']  = $row->estadoPlanDesc;
            }
            

            //$data['tablaMatrizAgendamiento'] = $tabla;
            $data['cmbBandaHoraria'] = $arrayData;
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function ingresarAgendamiento() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['submsj'] = null;
        $data['codigo'] = null;
        try {
            $idEmpresaColab    = $this->input->post('idEmpresaColab');
            $jefatura          = $this->input->post('jefatura');
            $fechaAgendamiento = $this->input->post('fechaAgendamiento');
            $idBandaHoraria    = $this->input->post('idBandaHoraria');
            $itemplan          = $this->input->post('itemplan');

            $nomContacto1   = $this->input->post('nomContacto1');
            $telefContacto1 = $this->input->post('telefContacto1');
            $nomContacto2   = $this->input->post('nomContacto2');
            $telefContacto2 = $this->input->post('telefContacto2');

            $flgFecha = $this->m_agendamiento->getFlgFecha($fechaAgendamiento);

            if($idEmpresaColab == null || $jefatura == null ||  $idBandaHoraria == null || $itemplan == null) {
                $data['submsj'] = 'Comunicarse con el programador';
                throw new Exception("ND");
            }

            if($flgFecha == 0) {
                $data['submsj'] = 'Elegir una fecha distinta.';
                throw new Exception("como mínimo se podrá agendar pasando 48 horas y hasta 9 días");
            }

            $row = $this->m_utils->getIdCuotaAgenda($idEmpresaColab, $jefatura, $idBandaHoraria);

            if(!$row) {
                $data['submsj'] = 'No tiene la banda horaria asociada a este itemplan';
                throw new Exception("No ingresó");
            }

            $countAgendamiento = $this->m_agendamiento->countAgendamientoByFecha($row->idCuotaAgenda, $fechaAgendamiento);

            if($countAgendamiento > 0) {
                $data['submsj'] = 'Ya tiene registrado la fecha y banda horaria seleccionada';
                throw new Exception("No ingresó");
            }

            $codigo = $this->m_agendamiento->getCodigoAgendamiento();

            $arrayAgendamiento = array(
                'idCuotaAgenda'      => $row->idCuotaAgenda,
                'fecha_registro'     => $this->fechaActual(),
                'fecha_agendamiento' => $fechaAgendamiento,
                'idUsuarioRegistro'  => $this->session->userdata('idPersonaSession'),
                'itemplan'           => $itemplan,
                'nomContacto1'       => $nomContacto1,
                'telefContacto1'     => $telefContacto1,
                'nomContacto2'       => $nomContacto2,
                'telefContacto2'     => $telefContacto2,
                'codigo'             => $codigo
            );

            $data = $this->m_agendamiento->registrarAgendamiento($arrayAgendamiento);
            $data['codigo'] = $codigo;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function getAgendamientosCalendar() {
        $arrayDataAgen = $this->m_agendamiento->getAgendamiento(NULL);
        $arry = array();
        $val = 1;
        foreach($arrayDataAgen as $row) {
            $rw = array();
            $rw['id']    = $val;
            $rw['title'] = 'Agendamiento '.$row['fecha_agendamiento'];
            $rw['class'] = "event-success";
            $rw['start'] = $row['fechaMilisec'];
            array_push($arry, $rw);
            $val++;
        }

        echo json_encode($arry, JSON_NUMERIC_CHECK);
    }

    function getDetalleAgendamientoByFecha() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $fecha = ($this->input->post('fecha') == null ? date('Y-m-d') : date('Y-m-d', ($this->input->post('fecha') / 1000) ) );

            if($fecha == null) {
                throw new Exception("ND fecha");
            }
            $data['error'] = EXIT_SUCCESS;
            $arrayDataAgen = $this->m_agendamiento->getAgendamiento($fecha);
            $tablaModalDetalleAgen = $this->getTablaDetalleAgendamiento($arrayDataAgen);
            $data['tablaDetalleAgenda'] = $tablaModalDetalleAgen;
            $data['fechaAgen'] = $fecha;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaDetalleAgendamiento($arrayDataAgen) {
        $html = '<table id="tbDetalleAgenda" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="color: white ; background-color: #3b5998">Itemplan</th>
                            <th style="color: white ; background-color: #3b5998">Jefatura</th>  
                            <th style="color: white ; background-color: #3b5998">EECC</th>
                            <th style="color: white ; background-color: #3b5998">Banda Horaria</th>
                            <th style="color: white ; background-color: #3b5998">Estado</th>                            
                            <th style="color: white ; background-color: #3b5998">Usuario Registro</th>
                            <th style="color: white ; background-color: #3b5998">Fecha Registro</th>                                                                             
                        </tr>
                    </thead>                    
                    <tbody>';

        foreach($arrayDataAgen as $row) {

        $html .='   <tr>
                        <td style="color: white ; background-color: #3b5998">'.$row['itemplan'].'</td>
                        <td>'.$row['jefatura'].'</td>
                        <td>'.$row['empresaColabDesc'].'</td>							
                        <th>'.$row['bandaHoraria'].'</th>
                        <th>'.$row['estado'].'</th>	
                        <th>'.$row['usuarioRegistro'].'</th>		
                        <th>'.$row['fecha_registro'].'</th>			                                                    				                        
                    </tr>';
        }
        $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function getTablaMatrizAgendamiento($idEmpresaColab, $jefatura) {
        $dataArray = $this->m_agendamiento->getMatrizAgendamiento($idEmpresaColab, $jefatura);
        $cont = 0;
        foreach($dataArray as $row) {
            $cont++;
            if($cont == 1) {
                $html = '<table id="data-table" class="table table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th style="color: white ; background-color: #3b5998">'.$row->bandaHoraria.'</th>
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_dos.'</th>  
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_tres.'</th>
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_cuatro.'</th>
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_cinco.'</th>                            
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_seis.'</th>
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_siete.'</th>
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_ocho.'</th>  
                                <th style="color: white ; background-color: #3b5998">'.$row->dia_nueve.'</th>                                                                                                                                                                                          
                            </tr>
                        </thead>                    
                    <tbody>';

                    $fecha_dia_dos    = $row->dia_dos; 
                    $fecha_dia_tres   = $row->dia_tres; 
                    $fecha_dia_cuatro = $row->dia_cuatro; 
                    $fecha_dia_cinco  = $row->dia_cinco; 
                    $fecha_dia_seis   = $row->dia_seis; 
                    $fecha_dia_siete  = $row->dia_siete; 
                    $fecha_dia_ocho   = $row->dia_ocho; 
                    $fecha_dia_nueve  = $row->dia_nueve;                     
            } else {
                $arrayData = array($row->dia_dos, $row->dia_tres, $row->dia_cuatro, $row->dia_cinco, $row->dia_seis, $row->dia_siete, $row->dia_ocho);
                $btn1 = ($row->dia_dos == '#D8D8D8')    ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_dos.'"    style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_dos.'"    data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn2 = ($row->dia_tres == '#D8D8D8')   ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_tres.'"   style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_tres.'"   data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn3 = ($row->dia_cuatro == '#D8D8D8') ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_cuatro.'" style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_cuatro.'" data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn4 = ($row->dia_cinco == '#D8D8D8')  ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_cinco.'"  style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_cinco.'"  data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn5 = ($row->dia_seis == '#D8D8D8')   ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_seis.'"   style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_seis.'"   data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn6 = ($row->dia_siete == '#D8D8D8')  ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_siete.'"  style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_siete.'"  data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn7 = ($row->dia_ocho == '#D8D8D8')   ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_ocho.'"   style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_ocho.'"   data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';
                $btn8 = ($row->dia_nueve == '#D8D8D8')  ? null : '<i class="zmdi zmdi-hc-2x zmdi-calendar-check" id="'.$row->idBandaHoraria.'_'.$fecha_dia_nueve.'"  style="cursor:pointer" data-fecha_agendamiento="'.$fecha_dia_nueve.'"  data-id_banda_horaria="'.$row->idBandaHoraria.'" data-banda_horaria="'.$row->bandaHoraria.'" onclick="agregarBandaHoraria($(this));"></i>';


                $html .='   <tr>
                                <td style="color: white ; background-color: #3b5998">'.$row->bandaHoraria.'</td>
                                <td style="background:'.$row->dia_dos.'">'.$btn1.'</td>
                                <td style="background:'.$row->dia_tres.'">'.$btn2.'</td>							
                                <th style="background:'.$row->dia_cuatro.'">'.$btn3.'</th>
                                <th style="background:'.$row->dia_cinco.'">'.$btn4.'</th>	
                                <th style="background:'.$row->dia_seis.'">'.$btn5.'</th>		
                                <th style="background:'.$row->dia_siete.'">'.$btn6.'</th>	
                                <th style="background:'.$row->dia_ocho.'">'.$btn7.'</th>	
                                <th style="background:'.$row->dia_nueve.'">'.$btn8.'</th>			                                                    				                        
                            </tr>';
            }
        }
        $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function getPanelMatrizAgendamiento() {
        $idEmpresaColab = $this->input->post('idEmpresaColab');
        $jefatura       = $this->input->post('jefatura');
        $tabla = $this->getTablaMatrizAgendamiento($idEmpresaColab, $jefatura);
        
        $data['tablaMatrizAgendamiento'] = $tabla;
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