<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_ficha_tecnica_sinfix extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');       
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            
	           $data['listaNivelesCali'] = $this->m_bandeja_ficha_tecnica->getNivelesCalibracion();
	           $data['listaTrabajos'] = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_COAXIAL_GENERICO);
	           $data['listaTrabajosFtth'] = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_FO_FTTH);
	           $data['listaTrabajosSisegos'] = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_FO_SISEGOS_SMALLCELL_EBC);
    	        if(@$_POST["pagina"]=="pendienteFiltro"){
	               if(@!$_POST["itemplan"]) {
	                   $_POST["itemplan"]="";
	               }
	               if(@!$_POST["proyecto"]) {
	                   $_POST["proyecto"]="";
	               }
	               if(@!$_POST["subproyecto"]) {
	                   $_POST["subproyecto"]="";
	               }
	               if(@!$_POST["selectFase"]) {
	                   $_POST["selectFase"]="";
	               }
	               //log_message('error', 'filtro pendienteFiltro.'.$_POST["proyecto"].'-'.$_POST["subproyecto"].'-'.$_POST["selectFase"].'-'.$_POST["itemplan"]);
	               $data["tabla"] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnica($_POST["subproyecto"],'','',$_POST["itemplan"],'', $this->session->userdata('eeccSession')));
	               
	           }else{
	               $data['tabla'] = $this->makeHTLMTablaBandejaAprobMo(null);
	           }
    	       $data['optionsTipoTra'] = $this->makeHTMLOptionsChoiceTipoTrabajo($this->m_bandeja_ficha_tecnica->getTipoTrabajoFichaTecnica());
	           $data["extra"]=' <link rel="stylesheet" href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css">
                                <link rel="stylesheet" href="'.base_url().'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>   
                                <link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
                                <link rel="stylesheet" href="'.base_url().'public/css/jasny-bootstrap.min.css">';
                                 $data["proyecto"]    = $this->LLenarProyecto();
	           $data["subproyecto"] = $this->LLenarSubProyecto(NULL);
	           $data["fase"]    = $this->LLenarFase();
	           $data["pagina"]="regFichaTec";
        	   $permisos =  $this->session->userdata('permisosArbol');
        	  // $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_INSPECCIONES, ID_PERMISO_HIJO_REGISTRO_FICHA);
        	  // $data['opciones'] = $result['html'];
        	  // if($result['hasPermiso'] == true){
        	       $this->load->view('vf_layaout_sinfix/header',$data);
        	       $this->load->view('vf_layaout_sinfix/cabecera');
        	       $this->load->view('vf_layaout_sinfix/menu');
        	       $this->load->view('vf_ficha_tecnica/v_registro_ficha_tecnica_sinfix',$data);
        	       $this->load->view('vf_layaout_sinfix/footer');
        	       
        	       $this->load->view('recursos_sinfix/js');        	       
        	       $this->load->view('recursos_sinfix/datatable2',$data);
        	       $this->load->view('recursos_sinfix/js_registro_ficha_tecnica',$data);
        	       $this->load->view('recursos_sinfix/fancy',$data);
        	       
        	  // }else{
        	  //     redirect('login','refresh');
	          // }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function makeHTMLOptionsChoiceTipoTrabajo($listaTipoTrabajo){
        $html = '';
        foreach($listaTipoTrabajo->result() as $row){
            $html .= '<option value="'.$row->id_ficha_tecnica_tipo_trabajo.'">'.$row->descripcion.'</option>';
        }       
        return $html;
    }

    public function makeHTLMTablaBandejaAprobMo($listaPTR){  
        $html = '<table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>                   
                        <tr>
                            <th></th>
                            <th>Item Plan</th>   
                            <th>Estacion</th> 
                            <th>Indicador</th>     
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fase</th>
                            <th>Fec. Prevista</th>
                            <th>Estado Plan</th>
                            <th>Fecha</th>
                            <th>Situacion</th>
                        </tr>
                    </thead>
                   
                    <tbody>';


	if($listaPTR!=null){
        foreach($listaPTR->result() as $row){
            $btnFotos = '';
           /*
           $btnFotos    = '<a data-item_plan="'.$row->itemPlan.'"onclick="openModalFotos($(this));" style="cursor:pointer;margin-left: 10px;">
                            <i class="fa fa-camera"></i>
                          </a>';
                          */
           $btnRegFicha = '';
           $colorRow    = '';
           /*
           if($row->id_ficha_tecnica != null && $row->idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA){//SI ESTA PENDIENTE Y EN OBRA, PUEDE LIQUIDAR
                   $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Asignar" href="#"  data-id_sub_proyecto="'.$row->idSubProyecto.'" data-item_plan="'.$row->itemPlan.'"onclick="cambiarEstadoPreLiquidado($(this));"><i class="fa fa-briefcase"></i></a>';
           }
		   */
                     
          if($row->id_ficha_tecnica_base == FICHA_COAXIAL_GENERICA){
               if($row->id_ficha_tecnica == null && $row->flg_evidencia == 1){
                    $btnRegFicha .= '<a style="cursor:pointer;" onclick="openModalRregistrarFicha(this)" data-itm ="'.$row->itemPlan.'" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';
               }else if($row->id_ficha_tecnica != null){
                   if($row->estado_vali == 'D.J. APROBADA' || $row->estado_vali == 'D.J. PDTE VALIDACION'){
                       $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDF?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';//PDF CONTRATA
                       if($row->estado_validacion != null){
                           $btnRegFicha .=  '<a style="cursor:pointer;margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEval(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
                       }
                   }else if($row->estado_vali == 'D.J. RECHAZADO'){//SI FUE RECHAZADO
                       $btnRegFicha .='<a style="cursor:pointer;" data-itm ="'.$row->itemPlan.'" onclick="openModalEditarFicha(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//PUEDE EDITAR FICHA                       
                   }
               }
           }else if($row->id_ficha_tecnica_base == FICHA_FO_FTTH_Y_OP){
               if($row->id_ficha_tecnica == null && $row->flg_evidencia == 1){//SI NO CUENTA CON FICHA 
                    $btnRegFicha .= '<a style="cursor:pointer;" onclick="openModalRregistrarFichaFO(this)" data-itm ="'.$row->itemPlan.'" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//REGISTRAR FICHA
               }else if($row->id_ficha_tecnica != null){//SI YA CUENTA CON FICHA TECNICA
                   if($row->estado_vali == 'D.J. APROBADA' || $row->estado_vali == 'D.J. PDTE VALIDACION'){
                       $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDFFO?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';//PDF CONTRATA
                       if($row->estado_validacion != null){
                           $btnRegFicha .=  '<a style="cursor:pointer;margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
                       }
                   }else if($row->estado_vali == 'D.J. RECHAZADO'){//SI FUE RECHAZADO
                       $btnRegFicha .='<a style="cursor:pointer;" data-itm ="'.$row->itemPlan.'" onclick="openModalEditarFichaFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//PUEDE EDITAR FICHA                    
                   }
               }
           }else if($row->id_ficha_tecnica_base == FICHA_FO_SISEGOS_SMALLCELL_EBC){
               /*
               if($row->id_ficha_tecnica == null && $row->flg_evidencia == 1){//SI NO CUENTA CON FICHA
                   $btnRegFicha .= '<a style="cursor:pointer;" onclick="openModalRregistrarFichaFoSisego(this)" data-itm ="'.$row->itemPlan.'" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//REGISTRAR FICHA
               }else if($row->id_ficha_tecnica != null){//SI YA CUENTA CON FICHA TECNICA
                   if($row->estado_vali == 'APROBADO' || $row->estado_vali == 'PENDIENTE'){
                       $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDFSI?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';//PDF CONTRATA
                       if($row->estado_validacion != null){
                           $btnRegFicha .=  '<a style="cursor:pointer;margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
                       }
                   }else if($row->estado_vali == 'RECHAZADO'){//SI FUE RECHAZADO
                       $btnRegFicha .='<a style="cursor:pointer;" data-itm ="'.$row->itemPlan.'" onclick="openModalEditarFichaFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//PUEDE EDITAR FICHA
                   }
               }
               */
               
               if($row->id_ficha_tecnica != null){//SI YA CUENTA CON FICHA TECNICA - LLENADO DESDE EL FORMULARIO DE EJECUCION
                    if($row->flg_evidencia == 1){//SI NO CUENTA CON EVIDENCIAS
                       $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="fa fa-download"></i></a>';
                       if($row->estado_vali == 'D.J. APROBADA' || $row->estado_vali == 'D.J. PDTE VALIDACION'){
                           $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDFSI?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';//PDF CONTRATA                          
                       }else if($row->estado_vali == 'D.J. RECHAZADO'){//SI FUE RECHAZADO
                           $btnRegFicha .='<a style="cursor:pointer;" data-itm ="'.$row->itemPlan.'" onclick="openModalEditarFichaFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//PUEDE EDITAR FICHA                         
                       }
                       if($row->estado_validacion != null){
                           $btnRegFicha .='<a style="cursor:pointer;margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalSI(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
                       }
                    }else{
                    $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="No cuenta con evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer"><i class="fa fa-upload"></i></a>';
                    }
               }else{
                   $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="No cuenta con formulario" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer"><i class="fa fa-exclamation-triangle"></i></a>';
               }
           }else if($row->id_ficha_tecnica_base == FICHA_FO_CV){
                if($row->id_ficha_tecnica != null){//SI YA CUENTA CON FICHA TECNICA - LLENADO DESDE EL FORMULARIO DE EJECUCION
                   if($row->flg_evidencia == 1){//SI NO CUENTA CON EVIDENCIAS
                       //$btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="fa fa-download"></i></a>';
                       if($row->estado_vali == 'D.J. APROBADA' || $row->estado_vali == 'D.J. PDTE VALIDACION'){
                           $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDFCV?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';//PDF CONTRATA
                       }else if($row->estado_vali == 'D.J. RECHAZADO'){//SI FUE RECHAZADO
                           $btnRegFicha .='<a style="cursor:pointer;" data-id_ficha="'.$row->id_ficha_tecnica.'" data-accion="'.EDITAR_REGISTRO.'" data-idsubpro="'.ID_SUB_PROYECTO_CV_INTEGRAL.'" data-itemplan="'.$row->itemPlan.'" onclick="editarKitMaterialFomFT($(this));"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';//PUEDE EDITAR FICHA
                           $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDFCV?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
                       }
                       if($row->estado_validacion != null){
                          // $btnRegFicha .='<a style="cursor:pointer;margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalSI(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
                       }
                   }else{
                       //$btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="No cuenta con evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer"><i class="fa fa-upload"></i></a>';
                   }
               }else{
                   $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="No cuenta con formulario" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer"><i class="fa fa-exclamation-triangle"></i></a>';
               }
             } else if($row->id_ficha_tecnica_base == FICHA_FO_OBRAS_PUBLICAS){
                if($row->id_ficha_tecnica != null){//SI YA CUENTA CON FICHA TECNICA - LLENADO DESDE EL FORMULARIO DE EJECUCION
                    if($row->flg_evidencia == 1){//SI NO CUENTA CON EVIDENCIAS
                        $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="fa fa-download"></i></a>';
                        if($row->estado_vali == 'D.J. APROBADA' || $row->estado_vali == 'D.J. PDTE VALIDACION'){
                            $btnRegFicha .= '<a style="margin-left: 10px;" href="makePDFOBP?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';//PDF CONTRATA
                        }
                        if($row->estado_validacion != null){
                            $btnRegFicha .='<a style="cursor:pointer;margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalOBP(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
                        }
                    }else{
                        $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="No cuenta con evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer"><i class="fa fa-upload"></i></a>';
                    }
                }else{
                    $btnRegFicha .= '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="No cuenta con formulario" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer"><i class="fa fa-exclamation-triangle"></i></a>';
                }
            } else{
               $colorRow = 'style="background: goldenrod;"';
        }
           
            $html .=' <tr '.$colorRow.'>
                        <th>'.$btnRegFicha.$btnFotos.'</th>
                        <th>'.$row->itemPlan.'</th>
                        <th>'.$row->estacionDesc.'</th>
                        <th>'.$row->indicador.'</th>
                        <th>'.$row->subProyectoDesc.'</th>
                        <th>'.$row->zonalDesc.'</th>
                        <th>'.$row->empresaColabDesc.'</th>
                        <th>'.$row->faseDesc.'</th>
                        <th>'.$row->fechaPrevEjec.'</th>
                        <th>'.$row->estadoPlanDesc.'</th>
                        <th>'.$row->fecha_registro.'</th>
                        <th>'.$row->estado_vali.'</th>
                    </tr>';
            }
        }
            $html .='</tbody>
                 </table>';
                    
        return utf8_decode($html);
    }
    
      function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('itemplanFil');
            $mesEjec = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnica($SubProy,$eecc,$zonal,$itemPlan,$mesEjec, $this->session->userdata('eeccSession')));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getInfoItemFichaTecnica(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $datosItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($itemplan);
            $data['itemplan']   = $datosItem['itemplan'];
            $data['subpro']     = $datosItem['subProyectoDesc'];            
            $data['nodo']       = $datosItem['codigo'];            
            $data['fec_inicio'] = $datosItem['fec_inicio'];            
            $data['fec_fin']    = $datosItem['fechaEjecucion'];
            $data['troba']      = $datosItem['indicador'];
            $data['serie']      = $datosItem['serie'];
            $data['nombreCuadri'] = '';
            $data['eecc']       = $datosItem['empresaColabDesc'];            
            $data['coordX']     = $datosItem['coordX'];
            $data['coordY']     = $datosItem['coordY'];
            $data['error']      = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }
    
    function registrarFichaTecnica(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $type           = $this->input->post('type');
            $itemplan       = $this->input->post('itemplan');
            $nombreJefe     = $this->input->post('txtNombreJefeCuadrilla');
            $codigoJefe     = $this->input->post('txtCodigo');
            $celularJefe    = $this->input->post('txtCelular');
            $hasPlano       = $this->input->post('radioPlano');
            $observacion    = $this->input->post('inputObservacion');
            $observacionAdi = $this->input->post('inputObservacionAdicional');
            $coorx          = $this->input->post('coorX');
            $coory          = $this->input->post('coorY');
            /***insertamos trabajos de la ficha tecnica***/
            $arrayTrabajo = array();
            $arrayNivelesCali = array();
            $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_COAXIAL_GENERICO);
            foreach($listaTrabajos->result() as $row){
                $datatrans = array();      
                $datatrans['id_ficha_tecnica_trabajo']      = $row->id_ficha_tecnica_trabajo;           
                $datatrans['id_ficha_tecnica_tipo_trabajo'] = $this->input->post('selectTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['cantidad']                      = $this->input->post('inputCantidadTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['observacion']                   = strtoupper($this->input->post('inputComentarioTrabajo'.$row->id_ficha_tecnica_trabajo));
                array_push($arrayTrabajo, $datatrans);              
            }  
            /***insertamos niveles de calibracion de la ficha tecnica***/            
            $listaNiveles = $this->m_bandeja_ficha_tecnica->getNivelesCalibracion();
            foreach($listaNiveles->result() as $row){
                $datatrans = array();
                $datatrans['id_ficha_tecnica_nivel_calibra']  = $row->id_ficha_tecnica_nivel_calibra;
                $datatrans['opt_recep']         = $this->input->post('opt1_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['opt_tx']            = $this->input->post('opt2_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['ch_30']             = $this->input->post('ch30_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['ch_75']             = $this->input->post('ch75_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['ch_113']            = $this->input->post('ch113_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['snr_ruido']         = $this->input->post('snr_'.$row->id_ficha_tecnica_nivel_calibra);                
                array_push($arrayNivelesCali, $datatrans); 
            } 
            $data = $this->m_bandeja_ficha_tecnica->insertFichaTecnica($coorx, $coory, $itemplan, strtoupper($nombreJefe), strtoupper($codigoJefe), $celularJefe, $hasPlano, strtoupper($observacion), strtoupper($observacionAdi), $arrayTrabajo, $arrayNivelesCali, $type);
            
            $SubProy    = $this->input->post('subProy');
            $eecc       = $this->input->post('eecc');
            $zonal      = $this->input->post('zonal');
            $itemPlan   = $this->input->post('itemplanFil');
            $mesEjec    = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnica($SubProy,$eecc,$zonal,$itemPlan,$mesEjec, $this->session->userdata('eeccSession')));
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getFotosEvi() {
        // $itemPlan     = $this->input->post('itemPlan');
        // $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan.'/COAXIAL';
        // $ub = scandir($ubicacion);
        // // $info = new SplFileInfo($ubicacion);
        // $files = array_diff(scandir($ubicacion), array('.', '..'));
        // if(is_dir($ubicacion)) {
        //     $cant = 100;
        // }
        $listImageFO       = null;
        $listImageCoaxial  = null;
        $listImageINSTROBA = null;
        $fotosFO      = array();
        $fotosCOAXIAL = array();
        $fotosArray   = array();
        $itemPlan     = $this->input->post('itemPlan');

        $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan;
        if(is_dir($ubicacion)) {
            $ubicacionFO       = 'uploads/evidencia_fotos/'.$itemPlan.'/FO';
            $ubicacionCOAXIAL  = 'uploads/evidencia_fotos/'.$itemPlan.'/COAXIAL';
            $ubicacionINSTROBA = 'uploads/evidencia_fotos/'.$itemPlan.'/INST. TROBA';
            if(is_dir($ubicacionFO)) {
                $arrayNameFO = array_diff(scandir($ubicacionFO), array('.', '..'));
                foreach($arrayNameFO as $foto) {
                    list($cordX, $cordY) = $this->writeFoto($itemPlan, $ubicacionFO, $foto, ID_ESTACION_FO);
                    $listImageFO.= '<li><div class="container__image"><img src="'.$ubicacionFO.'/'.$foto.'" alt="" class="image__">
                                            <div class="information__image"><p class="name__image">
                                                </p><a class="view__moreinfo">x:'.$cordX. '</br> y: '.$cordY.'</a>
                                            </div>
                                        </div>
                                    </li>'; 
                    // array_push($fotosArray, $row);
                }
            } if(is_dir($ubicacionCOAXIAL)) {
                $arrayNameCOAX = array_diff(scandir($ubicacionCOAXIAL), array('.','..'));

                foreach($arrayNameCOAX as $foto) {
                    list($cordX, $cordY) = $this->writeFoto($itemPlan, $ubicacionCOAXIAL, $foto, ID_ESTACION_COAXIAL);
                    $listImageCoaxial.= '<li><div class="container__image"><img src="'.$ubicacionCOAXIAL.'/'.$foto.'" alt="" class="image__">
                                                <div class="information__image"><p class="name__image">
                                                    </p><a class="view__moreinfo">x:'.$cordX. '</br> y: '.$cordY.'</a>
                                                </div>
                                            </div>
                                        </li>'; 
                    // array_push($fotosArray, $row);
                }
            } if(is_dir($ubicacionINSTROBA)) {
                $arrayNameTRO = array_diff(scandir($ubicacionINSTROBA), array('.', '..'));
                foreach($arrayNameTRO as $foto) {
                    list($cordX, $cordY)=$this->writeFoto($itemPlan, $ubicacionINSTROBA, $foto, ID_ESTACION_INS_TROBA);                    
                    $listImageINSTROBA.= '<li><div class="container__image"><img src="'.$ubicacionINSTROBA.'/'.$foto.'" alt="" class="image__">
                                                <div class="information__image"><p class="name__image">
                                                    </p><a class="view__moreinfo">x:'.$cordX. '</br> y: '.$cordY.'</a>
                                                </div>
                                              </div>
                                          </li>';
                    // array_push($fotosArray, $row);
                }
            } 
        }
        // $listImage = null;
        // foreach($fotosArray AS $foto) {
        //     if(is_dir($ubicacionFO)) {
        //         $listImage.= '<li><img src="'.$ubicacionFO.'/'.$foto.'" alt="" class="image__"></li>';  
        //     } if(is_dir($ubicacionCOAXIAL)) {
        //         $listImage.= '<li><img src="'.$ubicacionCOAXIAL.'/'.$foto.'" alt="" class="image__"></li>'; 
        //     }
        // }
        // $data['listFotos'] = $listImage;
        $data['listImageFO'] = $listImageFO;
        $data['listImageCO'] = $listImageCoaxial;
        $data['listImageINSTROBA'] = $listImageINSTROBA;
        
        echo json_encode(array_map('utf8_encode', $data));
    }

    function writeFoto($itemPlan, $ubicacion, $foto, $idEstacion) {
        $text = null;
        $data = $this->m_utils->getCoordenadasByEstaciones($itemPlan, $idEstacion);
        if($data['coordX'] != '' || $data['coordX'] != NULL || $data['coordY'] != '' || $data['coordY'] != NULL) {
            $text .= "X: ".$data['coordX']; 
            $text .= "/Y: ".$data['coordY'];
        }
        $fontSize = 65;
        $angle = 0;
        $text .= " ";
        //$text .= "Fecha: ".$this->fechaActual();
        $xPosition = 100; 
        $yPosition = 90; 
        $image = $ubicacion.'/'.$foto;
        $newImg = imagecreatefromjpeg($image);
        $font = 'public/fonts/gothic.ttf';
        $fontColor_red = imagecolorallocate($newImg, 255, 0, 0);
        imagettftext($newImg,$fontSize,$angle,$xPosition,$yPosition,$fontColor_red,$font,$text);
        imagejpeg($newImg,$image);
        imagedestroy($newImg);
        return array($data['coordX'], $data['coordY']);
    }
    
    function getFichaToEdit(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');            
            $datosItem= $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($itemplan);
            $data['itemplan'] = $datosItem['itemplan'];
            $data['subpro'] = $datosItem['subProyectoDesc'];
            $data['nodo'] = $datosItem['codigo'];
            $data['fec_inicio'] = $datosItem['fec_inicio'];
            $data['fec_fin'] = $datosItem['fechaEjecucion'];
            $data['troba'] = $datosItem['indicador'];
            $data['serie'] = $datosItem['serie'];
            $data['nombreCuadri'] = '';
            $data['eecc'] = $datosItem['empresaColabDesc'];
            $data['coordX'] = $datosItem['coordX'];
            $data['coordY'] = $datosItem['coordY'];
            
            $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($itemplan, FICHA_COAXIAL_GENERICA);
            $data['jefe_c_nombre'] = $dataFicha['jefe_c_nombre'];
            $data['jefe_c_celular'] = $dataFicha['jefe_c_celular'];
            $data['jefe_c_codigo'] = $dataFicha['jefe_c_codigo'];
            $data['observacion'] = $dataFicha['observacion'];
            $data['observacion_adicional'] = $dataFicha['observacion_adicional'];
            
            $dataTrabajo = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($itemplan, FICHA_COAXIAL_GENERICA)->result();       
            $data['dataTrabajo'] = json_encode($dataTrabajo);
            $dataNiveles= $this->m_bandeja_ficha_tecnica->getNivelesCalibracionByItemplan($itemplan)->result();  
            $data['dataNiveles'] = json_encode($dataNiveles);

            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }
    
    function getFichaToEditFO(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $datosItem= $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($itemplan);
            $data['itemplan'] = $datosItem['itemplan'];
            $data['subpro'] = $datosItem['subProyectoDesc'];
            $data['nodo'] = $datosItem['codigo'];
            $data['fec_inicio'] = $datosItem['fec_inicio'];
            $data['fec_fin'] = $datosItem['fechaEjecucion'];
            $data['troba'] = $datosItem['indicador'];
            $data['serie'] = $datosItem['serie'];
            $data['nombreCuadri'] = '';
            $data['eecc'] = $datosItem['empresaColabDesc'];
            $data['coordX'] = $datosItem['coordX'];
            $data['coordY'] = $datosItem['coordY'];
            
            $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($itemplan, FICHA_FO_FTTH_Y_OP);
            $data['jefe_c_nombre'] = $dataFicha['jefe_c_nombre'];
            $data['jefe_c_celular'] = $dataFicha['jefe_c_celular'];
            $data['jefe_c_codigo'] = $dataFicha['jefe_c_codigo'];
            $data['observacion'] = $dataFicha['observacion'];
            $data['observacion_adicional'] = $dataFicha['observacion_adicional'];
            
            $dataTrabajo = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($itemplan, FICHA_FO_FTTH_Y_OP)->result();
            $data['dataTrabajo'] = json_encode($dataTrabajo);
            
            $dataFileUpload = $this->makeArrayBySelect($this->m_bandeja_ficha_tecnica->getMedidasReflecoByItemplan($itemplan));           
            $data['htmlTablas'] = $this->makeHTMLBodyTable($dataFileUpload);
            $data['jsonDataFIle'] = json_encode(array_map('utf8_encode', $dataFileUpload)); 
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }
    
    public function makeArrayBySelect($listaDatos){
        $arra = array();
        foreach ($listaDatos->result() as $row){
            array_push($arra, $row->nodo."\t".$row->odf."\t".$row->cable_prim."\t".$row->num_fibra."\t".$row->divicau."\t".$row->divisor."\t".
                              $row->cto."\t".$row->distancia."\t".$row->atten_total_1310."\t".$row->reflectancia_1310."\t".$row->atten_total_1550."\t".$row->reflectancia_1550);            
        }
        return $arra;
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%d de %B del %Y");
        return $hoy;
    }
    
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// fichaFO
    
    function registrarFichaTecnicaFO(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $type           = $this->input->post('type');
            $itemplan       = $this->input->post('itemplan');
            $nombreJefe     = $this->input->post('txtNombreJefeCuadrilla2');
            $codigoJefe     = $this->input->post('txtCodigo2');
            $celularJefe    = $this->input->post('txtCelular2');
            $hasPlano       = $this->input->post('radioPlano2');
            $observacion    = $this->input->post('inputObservacion2');
            $observacionAdi = $this->input->post('inputObservacionAdicional2');
            $coorx          = $this->input->post('coorX');
            $coory          = $this->input->post('coorY');
            $jsonDataFile   = $this->input->post('jsonDataFile');
            /***insertamos trabajos de la ficha tecnica***/
            $arrayTrabajo = array();            
            $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_FO_FTTH);
            foreach($listaTrabajos->result() as $row){
                $datatrans = array();
                $datatrans['id_ficha_tecnica_trabajo']      = $row->id_ficha_tecnica_trabajo;
                $datatrans['id_ficha_tecnica_tipo_trabajo'] = $this->input->post('selectTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['cantidad']                      = $this->input->post('inputCantidadTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['observacion']                   = strtoupper($this->input->post('inputComentarioTrabajo'.$row->id_ficha_tecnica_trabajo));
                array_push($arrayTrabajo, $datatrans);
            }           
            /***insertamos medidas reflectometricas***/
                        
            $arrayFile = json_decode($jsonDataFile);
            $arrayFinal = array();
            if($arrayFile!=null){
                foreach($arrayFile as $linea){
                    $datos= preg_split("/[\t]/", $linea);
                    $dataMedida = array();
                    $dataMedida['nodo']              = $datos[0];
                    $dataMedida['odf']               = $datos[1];
                    $dataMedida['cable_prim']        = $datos[2];
                    $dataMedida['num_fibra']         = $datos[3];
                    $dataMedida['divicau']           = $datos[4];
                    $dataMedida['divisor']           = $datos[5];
                    $dataMedida['cto']               = $datos[6];
                    $dataMedida['distancia']         = $datos[7];
                    $dataMedida['atten_total_1310']  = $datos[8];
                    $dataMedida['reflectancia_1310'] = $datos[9];
                    $dataMedida['atten_total_1550']  = $datos[10];
                    $dataMedida['reflectancia_1550'] = $datos[11]; 
                    array_push($arrayFinal, $dataMedida);               
                }
            }

            $data = $this->m_bandeja_ficha_tecnica->insertFichaTecnicaFoFTTHOP($coorx, $coory, $itemplan, strtoupper($nombreJefe), strtoupper($codigoJefe), $celularJefe, $hasPlano, strtoupper($observacion), strtoupper($observacionAdi), $arrayTrabajo, $arrayFinal, $type);
            
            $SubProy    = $this->input->post('subProy');
            $eecc       = $this->input->post('eecc');
            $zonal      = $this->input->post('zonal');
            $itemPlan   = $this->input->post('itemplanFil');
            $mesEjec    = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnica($SubProy,$eecc,$zonal,$itemPlan,$mesEjec, $this->session->userdata('eeccSession')));
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function uploadFileReflectometricas(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/ficha_tecnica/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);

                if(count($comp)==CANTIDAD_COLUMNAS_TIPO_fICHA_FO_FTTH){
                  
                    $dataFileUpload = $this->readDataFromFile($uploadfile);
                    
                    $data['tablaData'] = $this->makeHTMLBodyTable($dataFileUpload);
                    $data['jsonDataFIle'] = json_encode(array_map('utf8_encode', $dataFileUpload));    
                    $data['error'] = EXIT_SUCCESS;
                
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (12 columnas separados por tabulaciones.)');
                }
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));     
    }
    
    
    
    public function readDataFromFile($uploadfile){
        $arra = array();
        $html = '';
        //$this->session->set_flashdata('item', $value);       
        $fp = fopen($uploadfile, "r");       
        while(!feof($fp)) {
            $linea = fgets($fp);
            $datos= preg_split("/[\t]/", $linea);
            if(count($datos)==CANTIDAD_COLUMNAS_TIPO_fICHA_FO_FTTH){
                array_push($arra, $linea);
            }
        }
        fclose($fp);
        return $arra;
    }
    
    public function makeHTMLBodyTable($listaDatos){
        $html = '';
        $indice = 0;
        foreach ($listaDatos as $linea){
            $datos= preg_split("/[\t]/", $linea);
            $html .= '<tr id="tr'.$indice.'">          
                        <th><a style="cursor:pointer;" data-indice="'.$indice.'" onclick="removeTR(this)"><img alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                    	<th>'.$datos[0].'</th>
                    	<th>'.$datos[1].'</th>
                    	<th>'.$datos[2].'</th>
                    	<th>'.$datos[3].'</th>
                    	<th>'.$datos[4].'</th>
                    	<th>'.$datos[5].'</th>
                    	<th>'.$datos[6].'</th>
                    	<th>'.$datos[7].'</th>
                    	<th>'.$datos[8].'</th>
                    	<th>'.$datos[9].'</th>
                    	<th>'.$datos[10].'</th>
                    	<th>'.$datos[11].'</th>
                	</tr>';
            $indice++;
        }
        return $html;
    }
    
    function makePDFFO(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
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
        
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_FTTH_Y_OP);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            <p style="text-align: center;">&nbsp;</p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
            <td style="width: 50%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
            <td style="width: 20%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
            </tr>
            </tbody>
            </table>
            <p><br /><br /></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 25%;"><strong>Fecha Inicio:</strong>'.$dataItem['fec_inicio'].'</td>
            <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaPreLiquidacion'].'</td>
            <td style="width: 25%;"><strong>Troba:</strong>'.$dataItem['indicador'].'</td>
            <td style="width: 25%;"><strong>Serie:</strong>'.$dataItem['serie'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 40%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
            <td style="width: 20%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
            <td style="width: 20%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
            <td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
            <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 20%;">&nbsp;</th>
            <th style="width: 15%;text-align: center;"><strong>CANTIDAD</strong></th>
            <th style="width: 15%;text-align: center;"><strong>TIPO</strong></th>
            <th style="width: 50%;text-align: center;"><strong>OBSERVACIONES</strong></th>
            </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_FO_FTTH_Y_OP);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
            <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
            <th style="text-align: center;">'.$row->cantidad.'</th>
            <th>'.$row->tipo_trabajo.'</th>
            <th>'.$row->observacion.'</th>
            </tr>';
        }
        $tbl .='</tbody>
            </table>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>MEDIDAS REFLECTOMETRICAS</strong></span></p>';
        $tbl .='<table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
                <tr role="row">                           
                    <th style="width: 68%; text-align: center;" colspan="9"></th>
                    <th style="width: 16%; text-align: center;" colspan="2"><strong>1310 nm</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="2"><strong>1550 nm</strong></th>
                  	                                                                                   
               </tr>
               <tr role="row">      
                	<th style="width: 4%;" colspan="1"></th> 
                    <th style="width: 8%;" colspan="1"><strong>NODO</strong></th> 
                    <th style="width: 8%;" colspancolspan="1"><strong>ODF</strong></th>                          
                    <th style="width: 8%;" colspancolspan="1"><strong>CABLE PRIM</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>NÃƒâ€šÃ‚Â° FIBRA</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>DIVICAU</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>DIVISOR</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>CTO</strong></th>      
                    <th style="width: 8%;" colspancolspan="1"><strong>DISTANCIA (KM)</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Atten. Total(db) >23db</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Reflectancia(ORL) >40 db</strong></th>               
                    <th style="width: 8%;" colspancolspan="1"><strong>Atten. Total(db) >23db</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Reflectancia(ORL) >40 db</strong></th> 
                </tr>
            </thead>
            <tbody>';
        $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getMedidasReflectometricasByItemplan($item);
        $cont = 1;
        foreach($listaNivCalibra->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 8%; text-align: center;">'.$row->nodo.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->odf.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->cable_prim.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->num_fibra.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->divicau.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->divisor.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->cto.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->distancia.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->atten_total_1310.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->reflectancia_1310.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->atten_total_1550.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->reflectancia_1550.'</th>
                    </tr>';
            $cont ++;
        }
        $tbl .='</tbody>
            </table>';
        $tbl .='<table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion_adicional'].'</td>
            </tr>
            </tbody>
            </table>';
        
        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');
    }
    
    function registrarFichaTecnicaSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $type           = $this->input->post('type');
            $itemplan       = $this->input->post('itemplan');
            $nombreJefe     = $this->input->post('txtNombreJefeCuadrilla3');
            $codigoJefe     = $this->input->post('txtCodigo3');
            $celularJefe    = $this->input->post('txtCelular3');
            $hasPlano       = $this->input->post('radioPlano');
            $observacion    = $this->input->post('inputObservacion3');
            $observacionAdi = $this->input->post('inputObservacionAdicional3');
            $coorx          = $this->input->post('coorX');
            $coory          = $this->input->post('coorY');
            /***insertamos trabajos de la ficha tecnica***/
            $arrayTrabajo = array();
           
            $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_FO_SISEGOS_SMALLCELL_EBC);
            foreach($listaTrabajos->result() as $row){
                $datatrans = array();
                $datatrans['id_ficha_tecnica_trabajo']      = $row->id_ficha_tecnica_trabajo;
                $datatrans['id_ficha_tecnica_tipo_trabajo'] = $this->input->post('selectTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['cantidad']                      = $this->input->post('inputCantidadTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['observacion']                   = strtoupper($this->input->post('inputComentarioTrabajo'.$row->id_ficha_tecnica_trabajo));
                array_push($arrayTrabajo, $datatrans);
            }
            /***insertamos medidas reflectometricas de la ficha tecnica***/
            $arrayMedReflec= array();//SON 3 POR DEFECTOS           
            for($i = 1; $i<=3; $i++){
                $datatrans = array();
                $datatrans['cable']             = $this->input->post('inputCable'.$i);
                $datatrans['ura']               = $this->input->post('inputUra'.$i);
                $datatrans['asig_origen']       = $this->input->post('inputAsigOri'.$i);
                $datatrans['asig_extremo']      = $this->input->post('inputAsigExt'.$i);
                $datatrans['distancia_optica']  = $this->input->post('inputDistOpti'.$i);
                $datatrans['att_total']         = $this->input->post('inputAttTotal'.$i);              
                array_push($arrayMedReflec, $datatrans);
            }
            
            $arrayMedPotenc= array();//SON 2 POR DEFECTOS
            for($i = 1; $i<=2; $i++){
                $datatrans = array();
                $datatrans['puerto_origen']      = $this->input->post('inputPuerto'.$i);
                $datatrans['ura_db_input']       = $this->input->post('inputDbUra'.$i);
                $datatrans['long_ura_cto_aprox'] = $this->input->post('inputCtoAprox'.$i);
                $datatrans['cro_cto_cuenta']     = $this->input->post('inputNumCto'.$i);
                $datatrans['cto_nap_output_db']  = $this->input->post('inputOutDbCto'.$i);
                $datatrans['long_acomet_aprox']  = $this->input->post('inputAcomeAprox'.$i);
                $datatrans['output_db_cliente']  = $this->input->post('inputOutDbCli'.$i);
                array_push($arrayMedPotenc, $datatrans);
            }
            
            $data = $this->m_bandeja_ficha_tecnica->insertFichaTecnicaFoSisegos($coorx, $coory, $itemplan, $nombreJefe, $codigoJefe, $celularJefe, $hasPlano, $observacion, $observacionAdi, $arrayTrabajo, $arrayMedReflec, $arrayMedPotenc, $type);
            
            $SubProy    = $this->input->post('subProy');
            $eecc       = $this->input->post('eecc');
            $zonal      = $this->input->post('zonal');
            $itemPlan   = $this->input->post('itemplanFil');
            $mesEjec    = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnica($SubProy,$eecc,$zonal,$itemPlan,$mesEjec, $this->session->userdata('eeccSession')));
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makePDFSI(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
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
        
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            <p style="text-align: center;">&nbsp;</p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
            <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
            <td style="width: 25%;"><strong>Sisego: </strong>'.$dataItem['indicador'].'</td>
            
            </tr>
            </tbody>
            </table>
            <p><br /><br /></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 25%;"><strong>Fecha Inicio: </strong>'.date("d/m/Y",  strtotime($dataItem['fec_inicio'])).'</td>
            <td style="width: 25%;"><strong>Fecha Fin: </strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
            <td style="width: 25%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
            <td style="width: 25%;"><strong>Serie Troba: </strong>'.$dataItem['serie'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 50%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
            <td style="width: 25%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
            <td style="width: 25%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
            <!--<td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>-->
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
            <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 20%;">&nbsp;</th>
            <th style="width: 15%;text-align: center;"><strong>CANTIDAD</strong></th>
            <th style="width: 15%;text-align: center;"><strong>TIPO</strong></th>
            <th style="width: 50%;text-align: center;"><strong>OBSERVACIONES</strong></th>
            </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
            <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
            <th style="text-align: center;">'.$row->cantidad.'</th>
            <th>'.$row->tipo_trabajo.'</th>
            <th>'.$row->observacion.'</th>
            </tr>';
        }
        $tbl .='</tbody>
          </table><table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion'].'</td>
            </tr>
            </tbody>
            </table>';/*
<p><span style="text-decoration: underline;"><strong>2)Medidas Reflectometricas End To End:</strong></span></p>';
$tbl .='<table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
               <tr role="row">
                	<th style="width: 4%; TEXT-ALIGN: center;" colspan="1"></th> 
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>CABLE</strong></th> 
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>URA</strong></th>                          
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ASIGNACION ORIGEN</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ASIGNACION EXTREMO</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>DISTANCIA OPTICA(KM)</strong></th>   
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ATT TOTAL(DB)</strong></th>
                </tr>
            </thead>
            <tbody>';
        $medEndToEnd = $this->m_bandeja_ficha_tecnica->getMedReflecEndToEnd($item);
        $cont = 1;
        foreach($medEndToEnd->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 16%; text-align: center;">'.$row->cable.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->ura.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->asig_origen.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->asig_extremo.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->distancia_optica.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->att_total.'</th>
                    </tr>';
            $cont ++;
        }
        $tbl .='</tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>3)Medidas De Potencia : Atenuacion Max : >-11db En CTO / >-12db Cliente Sisego/Small Cell / EBC:</strong></span></p>';
$tbl .='<table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
                <tr role="row">
               		<th style="width: 4%; text-align: center;" colspan="1"></th>    
                    <th style="width: 19%; text-align: center;" colspan="1"><strong>Equipo Origen</strong></th>
                    <th style="width: 10%; text-align: center;" colspan="1"><strong>URA</strong></th>    
                    <th style="width: 10%; text-align: center;" colspan="1"><strong>Long. FO /Ura - CTO</strong></th> 
                    <th style="width: 29%; text-align: center;" colspan="2"><strong>CTO / NAP</strong></th> 
                    <th style="width: 18%; text-align: center;" colspan="1"><strong>Long. FO Acomet.</strong></th> 
                    <th style="width: 10%; text-align: center;" colspan="1"><strong>CLIENTE</strong></th> 
               </tr>
              
               <tr role="row">
                	<th style="width: 4%; TEXT-ALIGN: center;" colspan="1"></th> 
                    <th style="width: 19%; TEXT-ALIGN: center;" colspan="1"><strong>PUERTO ORIGEN</strong></th> 
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>INPUT (DB)</strong></th>                          
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>APROX. (KM)</strong></th>
                    <th style="width: 19%; TEXT-ALIGN: center;" colspan="1"><strong>Nï¿½â€¹ CTO CUENTA</strong></th>
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>OUTPUT (dB)</strong></th>   
                    <th style="width: 18%; TEXT-ALIGN: center;" colspan="1"><strong>APROX. (KM)</strong></th>
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>OUTPUT (dB)</strong></th>                            
                </tr>
            </thead>
            <tbody>';
        $medidasPotencia = $this->m_bandeja_ficha_tecnica->getMedidasDePotencia($item);
        $cont = 1;
        foreach($medidasPotencia->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 19%; text-align: center;">'.$row->puerto_origen.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->ura_db_input.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->long_ura_cto_aprox.'</th>
                        <th style="width: 19%; text-align: center;">'.$row->cro_cto_cuenta.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->cto_nap_output_db.'</th>
                        <th style="width: 18%; text-align: center;">'.$row->long_acomet_aprox.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->output_db_cliente.'</th>                       
                    </tr>';
            $cont ++;
        }
        $tbl .='</tbody>
            </table>';
        $tbl .='<table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion_adicional'].'</td>
            </tr>
            </tbody>
            </table>';*/
        
        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');
    }
    
    function makePDFCV(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
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
        
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnicaCV($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_CV);
        $tbl ='<!--<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">-->
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            <p style="text-align: center;">&nbsp;</p>
            <table style="height: 100%; width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 25%;"><strong>ITEMPLAN: </strong>'.$dataItem['itemplan'].'</td>
                        <td style="width: 40%;"><strong>SUB PROYECTO: </strong>'.$dataItem['subProyectoDesc'].'</td>
                        <td style="width: 15%;"><strong>NODO: </strong>'.$dataItem['codigo'].'</td>
                        <td style="width: 20%;"><strong>FECHA FIN:</strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
                    </tr>
                </tbody>
            </table>
           <br></br><br></br>
            <table style="height: 100%; width: 100%;">
                <tbody>
                    <tr>            
                        <td style="width: 55%;"><strong>DIRECCION: </strong>'.$dataItem['direccion'].'</td>  
                        <td style="width: 14%;"><strong>NUMERO: </strong>'.$dataItem['numero'].'</td>
                        <td style="width: 14%;"><strong>PISOS: </strong>'.$dataItem['pisos'].'</td>
                        <td style="width: 17%;"><strong>DEPARTAMENTOS: </strong>'.$dataItem['depa'].'</td>      
                    </tr>
                </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 50%;"><strong>NOMBRE:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
            <td style="width: 25%;"><strong>CODIGO:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
            <td style="width: 25%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>INFORMACION DE MATERIALES</strong></span></p>
            <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 5%;">&nbsp;</th>
            <th style="width: 15%;text-align: center;"><strong>CODIGO</strong></th>
            <th style="width: 70%;text-align: center;"><strong>MATERIAL</strong></th>
            <th style="width: 10%;text-align: center;"><strong>TOTAL</strong></th>
            </tr>';
        $listaMateriales = $this->m_bandeja_ficha_tecnica->getMaterialesCVByItemplan($item);
        $cont = 1;
        foreach($listaMateriales->result() as $row){
            $tbl .='<tr>
            <th style="text-align: left;"><strong>'.$cont.'</strong></th>
            <th style="text-align: center;">'.$row->id_material.'</th>
            <th>'.$row->descrip_material.'</th>
            <th style="text-align: center;">'.$row->total.'</th>
            </tr>';
            $cont++;
        }
        $tbl .='<tr>
            <th style="text-align: left;"><strong>'.$cont.'</strong></th>
            <th style="text-align: center;">--------------</th>
            <th>MICROCANOLIZADO</th>
            <th style="text-align: center;">'.$dataItem['microcanolizado'].'</th>
            </tr>';
        
        
        $tbl .='</tbody>
            </table>
<br></br><br></br>
<table style="height: 100%; width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 25%;"><strong>INSTALACION CTO: </strong>'.$dataItem['instalacion_cto'].'</td>
                     
                    </tr>
                </tbody>
            </table>';
            
        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');
    }
    
    function makePDFOBP() {
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        $flg  = (isset($_GET['flg']) ? $_GET['flg'] : '');
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
        $pdf->SetHeaderMargin(30);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        
        $dataItem  = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_OBRAS_PUBLICAS);
        $title = ($flg == 1) ? NULL : '<p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>';

        $tbl ='<img style="width: 100px; heigth:40px">
                '.$title.'
                <p style="text-align: center;">&nbsp;</p>
                <table style="height: 100%; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 30%;"><strong>Itemplan:     </strong>'.$dataItem['itemplan'].'</td>
                            <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
                            <td style="width: 25%;"><strong>Sisego:       </strong>'.utf8_decode($dataItem['indicador']).'</td>
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 30%;"><strong>Fecha Inicio: </strong>'.date("d/m/Y",  strtotime($dataItem['fec_inicio'])).'</td>
                            <td style="width: 45%;"><strong>Fecha Fin:    </strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
                            <td style="width: 25%;"><strong>Nodo:         </strong>'.$dataItem['codigo'].'</td>
                        </tr>
                    </tbody>
                </table>
                <p>
                    <span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span>
                </p>
                <table style="height: 100%; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 50%;"><strong>Nombre:</strong>'.utf8_decode($dataFicha['jefe_c_nombre']).'</td>
                            <td style="width: 25%;"><strong>EECC :</strong>'.$dataItem['empresaColabDesc'].'</td>
                        </tr>
                    </tbody>
                </table>
                <p><span style="text-decoration: underline;"><strong>DATOS</strong></span></p>
                <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                    <tbody>
                        <tr>
                            <th style="width: 10%;text-align: center;"><strong>PTR</strong></th>
                            <th style="width: 10%;text-align: center;"><strong>Canalizaci&oacute;n KM</strong></th>
                            <th style="width: 10%;text-align: center;"><strong>Camaras Und</strong></th>
                            <th style="width: 7%;text-align: center;"><strong>C (Postes)</strong></th>
                            <th style="width: 7%;text-align: center;"><strong>MA (Postes)</strong></th>
                            <th style="width: 10%;text-align: center;"><strong>KM Tritubo</strong></th>
                            <th style="width: 10%;text-align: center;"><strong>Km Par cobre</strong></th>
                            <th style="width: 10%;text-align: center;"><strong>Km Cable coaxial</strong></th>
                            <th style="width: 10%;text-align: center;"><strong>Km FO</strong></th>
                            <th style="width: 15%;text-align: center;"><strong>Observaci&oacute;n</strong></th>
                        </tr>';
        $arrayDataFormulario = $this->m_bandeja_ficha_tecnica->getDataFormularioObrasPublicas($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
        foreach($arrayDataFormulario as $row) {
            $tbl .='<tr>
            <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
            <th style="text-align: center;">'.$row->canalizacion_km.'</th>
            <th style="text-align: center;">'.$row->camaras_und.'</th>
            <th style="text-align: center;">'.$row->c_postes.'</th>
            <th style="text-align: center;">'.$row->ma_postes.'</th>
            <th style="text-align: center;">'.$row->km_tritubo.'</th>
            <th style="text-align: center;">'.$row->km_par_cobre.'</th>
            <th style="text-align: center;">'.$row->km_cable_coax.'</th>
            <th style="text-align: center;">'.$row->km_fo.'</th>
            <th style="text-align: center;">'.$row->observacion.'</th>
            </tr>';
        }
        $comentario = null;
        if($dataFicha['observacion'] != null) {
            $comentario = 'Comentario';
        }
        $tbl .='    </tbody>
                </table>
                <table style="height: 100%; width: 100%;">
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 100%;"><strong>'.$comentario.'</strong></td>
                        </tr>
                        <tr>
                            <td>'.utf8_decode($dataFicha['observacion']).'</td>
                        </tr>
                    </tbody>
                </table>';

        if($flg == '' || $flg == 2) {
           if($flg == 2) {
                $tbl .='<div>
                            <label>Observaci&oacute;n TDP</label>
                        </div>
                        <div>
                            <label>'.utf8_decode($dataFicha['observacion_tdp']).'</label>
                        </div>';
            }
            $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
            //ob_clean();
            $pdf->Output('pdfexample.pdf', 'I');
        } else {
            if($dataFicha['estado_validacion'] == 1 || $dataFicha['estado_validacion'] == 2) {
                $tbl .='<div>
                            <label>Observaci&oacute;n</label>
                        </div>
                        <div>
                            <label>'.utf8_decode($dataFicha['observacion_tdp']).'</label>
                        </div>';
            } else {
                $tbl .='<div class="col-sm-12 form-group">
                            <label>Ingresar Observaci&oacute;n</label>
                            <textarea id="observacionOP" style="width: 100%;height:60px">'.$dataFicha['observacion_tdp'].'</textarea>
                        </div>
                        <div class="col-sm-12 form-group">
                            <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFicOBP(this)" type="button" class="btn btn-danger">RECHAZAR</button>
                            <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="1" onclick="validarFicOBP(this)" type="button" class="btn btn-primary">APROBAR</button>
                        </div>';
            }
            $data['dataHTML'] = $tbl;
            echo json_encode(array_map('utf8_encode', $data));
        }
       
    }
    
    function reactivarFichaTecnicaCV() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idFichaTecnica = $this->input->post('idFicha');
            $itemplan       = $this->input->post('itemplan');
            $arrayDatos = array('usuario_validacion'  =>  $this->session->userdata('idPersonaSession'),
                'fecha_validacion'  =>  date("Y-m-d H:i:s"),
                'estado_validacion' =>  ''
            );
            $data       = $this->m_bandeja_ficha_tecnica->reactivarFichaCV($itemplan, $idFichaTecnica, $arrayDatos);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     function LLenarProyecto(){
        $option="";
        $proyecto=$this->M_generales->ListarProyecto();
        foreach ($proyecto->result() as $row) {
            $option.='<option value="'.$row->idProyecto.'">'.$row->proyectoDesc.'</option>';
        }
        return $option;
    }
    
    function LLenarFase(){
        $option="";
        $fase = $this->m_utils->getAllFase();
        foreach ($fase->result() as $row) {
            $option.='<option value="'.$row->idFase.'">'.$row->faseDesc.'</option>';
        }
        return $option;
    }
    
    function LLenarSubProyecto($idProyecto){
        $option='<option value="0">Seleccionar SubProyecto</option>';
        $subproyecto=$this->M_generales->ListarSubProyecto($idProyecto);
        foreach ($subproyecto->result() as $row) {
            $option.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
        }
        return $option;
    }
}