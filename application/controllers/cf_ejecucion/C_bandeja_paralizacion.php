<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_paralizacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        // $this->load->model('mf_plan_obra/m_bandeja_paralizacion');
        $this->load->model('mf_utils/m_utils');
		$this->load->model('mf_ejecucion/M_pendientes');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $data["extra"]='<link rel="stylesheet" href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css">
                            <link rel="stylesheet" href="'.base_url().'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>   
                            <link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
                            <link rel="stylesheet" href="'.base_url().'public/css/jasny-bootstrap.min.css">';
            $data["pagina"]="Bandeja Paralizacion";
            $data['tablaParalizados'] = $this->getBandejaParalizacion();
            $permisos =  $this->session->userdata('permisosArbol');
            $this->load->view('vf_layaout_sinfix/header',$data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');
            $this->load->view('vf_ejecucion/v_bandeja_paralizacion',$data);
            $this->load->view('vf_layaout_sinfix/footer');
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getBandejaParalizacion() {
        $arrayData=$this->m_utils->getParalizacion(null, FLG_ACTIVO);   
        $zipSinfixAnterior = null;
        $html='
           <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
               <thead>
                     <tr class="table-primary">
                         <th>Acci&oacute;n</th>
                         <th>ItemPlan</th>
                         <th>Fase</th>
                         <th>Fecha Paralizaci&oacute;n</th>
                         <th>Estado Actual</th>
                         <th>Motivo</th>
                         <th>Comentario</th>
                         <th>Origen</th>
						 <th>Usuario</th>
                     </tr>
               </thead>
               <tbody>';            
        foreach($arrayData as $row){
            if($row->ubicacionEvidencia != null || $row->ubicacionEvidencia != '') {
                $zipSinfixAnterior = '<a href="'.$row->ubicacionEvidencia.'" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descargar zip" data-item_plan="'.$row->itemplan.'" style="cursor:pointer"><i class="fa fa-download"></i></a>';
            }
            
            $boton_revert='<a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Revertir" 
                            data-itemplan="'.$row->itemplan.'" onclick="openModalAlert($(this))"><i class="fa fa-retweet"></i></a>';
			/*no revertir paralizados o nuevo motivo pedido Owen 13.02.2020*/
			$countExitParaliza = $this->m_utils->getCountSisegoParalizacionExitosa($row->itemplan);//lista de itemplans brindados para no desparalizar
			if($row->idMotivo == 11 || $row->idMotivo == 66 || $row->idMotivo == 67 || $row->idMotivo == 68 || $row->idMotivo == 70 || $row->idMotivo == 72 /* ||$countExitParaliza == 0*/){
                $boton_revert ='Rev. Suspendida';                
            }
            $html.='<tr>
                        <td>
                        '.$boton_revert.' '.$zipSinfixAnterior.'                
                        </td>
                        <td>'.$row->itemplan.'</td>
                        <td>'.$row->faseDesc.'</td>
                        <td>'.$row->fechaRegistro.'</td>
                        <td>'.$row->estadoPlanDesc.'</td>                 
                        <td>'.utf8_decode($row->motivo).'</td>
                        <td>'.$row->comentario.'</td>
                        <td>'.$row->origen.'</td>
						<td>'.utf8_decode($row->usuario).'</td>    
                    </tr>';
        }
        $html.="</tbody>
            </table>";    
        return $html;
    }
	
	function insertParalizacion() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idMotivo   = $this->input->post('idMotivo');
            $comentario = $this->input->post('comentario');
            $itemplan   = $this->input->post('itemplan');
            $motivo     = $this->input->post('motivo');
            $origen     = $this->input->post('origen');
            
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            
            if($itemplan == '' || $itemplan == null) {
                throw new Exception('Itm');
            }

            if($idMotivo == '' || $idMotivo == null) {
                throw new Exception('debe seleccionar un motivo');
            }
            
            if($origen == '' || $origen == null) {
                throw new Exception('origen null');
            }
			
			$idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
			
			if($idEstadoPlan == 4 || $idEstadoPlan == 9) {
				throw new Exception('No se puede paralizar Obras en estado Terminado o Preliquidado.');
			}
            
            $ubicacion = null;
            $dataArray = array(
                                'itemplan'           => $itemplan,
                                'idMotivo'           => $idMotivo,
                                'comentario'         => $comentario,
                                'fechaRegistro'      => $this->fechaActual(),
                                'idUsuario'          => $this->session->userdata('idPersonaSession'),
                                'flg_activo'         => FLG_ACTIVO,
                                'ubicacionEvidencia' => $ubicacion,
                                'flgEstado'          => $origen
                              );
            $data = $this->M_pendientes->insertParalizacion($dataArray);
            
            $arrayDataItem = array('has_paralizado' => 1,
                                'fecha_paralizado'  => $this->fechaActual(),
                                'motivo_paralizado' =>  $idMotivo,
                                'fecha_reactiva_paralizado' =>  null
            );
            $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);
            
             $arrayDataLog = array(
                                'tabla'            => 'planobra',
                                'actividad'        => 'Paralizacion Web PO',
                                'itemplan'         => $itemplan,
                                'fecha_registro'   => $this->fechaActual(),
                                'id_usuario'       => $this->session->userdata('idPersonaSession')
                            );

            $this->m_utils->registrarLogPlanObra($arrayDataLog);
            
            $dataSend = ['itemplan'      => $itemplan,
                         'fecha'         => $this->fechaActual(),
                         'flg_activo'    => FLG_ACTIVO,
                         'motivo'        => $motivo,
                         'nombreUsuario' => $this->session->userdata('usernameSession'),
                         'correo'        => $this->session->userdata('email'),
                         'comentario'    => $comentario];

            $url = 'https://172.30.5.10:8080/obras2/recibir_par.php';

			$data = _trama_sisego($dataSend, $url, 7, $itemplan, 'ENVIAR PARALIZACION', NULL);

        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));        
    }

    function revertirParalizacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $itemplan = $this->input->post('itemplan');

            if($itemplan == '' || $itemplan == null) {
                throw new Exception("Itmp null");
            }
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if($idUsuario == null || $idUsuario == '') {
				throw new Exception('la sesi&oacute;n a terminado, recargar la p&aacute;gina.');
			}
            
            $arrayDataItem = array('has_paralizado' => null,
                'fecha_paralizado'  => null,
                'motivo_paralizado' =>  null,
                'fecha_reactiva_paralizado' =>  $this->fechaActual()
            );
            
            $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);

            $dataArray = array('flg_activo'        => FLG_INACTIVO,
                               'fechaReactivacion' => $this->fechaActual(),
							   'idUsuarioReac'     => $idUsuario);

            $data = $this->m_utils->updateFlgParalizacion($itemplan, FLG_ACTIVO, $dataArray);
                        
            $dataSend = ['itemplan'      => $itemplan,
                         'fecha'         => $this->fechaActual(),
                         'flg_activo'    => FLG_INACTIVO,
                         'nombreUsuario' => $this->session->userdata('usernameSession'),
                         'correo'        => $this->session->userdata('email')];
    
            $url = 'https://172.30.5.10:8080/obras2/recibir_par.php';
            
			$data = _trama_sisego($dataSend, $url, 8, $itemplan, 'ENVIAR REVER PARALIZACION', NULL);

        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}