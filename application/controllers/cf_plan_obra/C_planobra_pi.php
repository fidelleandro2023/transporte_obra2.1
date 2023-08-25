<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_planobra_pi extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra_pi');
        $this->load->model('mf_plan_obra/m_planobra');
        //$this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
		$this->load->library('map_utils/coordenadas_utils');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                /*carga de la tabla */           
               //$data['listartabla'] = $this->makeHTMLTablaCentral($this->m_planobra->getAllPlanesObra());
                /*carga de proyecto*/
               /*********miguel rios 13062018***********/
               /***$data['listaProy'] = $this->m_utils->getAllProyecto();***/
                $data['listaProy'] = $this->m_utils->getAllProyectoPI();
               /****************************************/
               $data['listaTiCen'] = $this->m_utils->getAllCentralPqt();
              /*carga de empresas electricas*/
               $data['listaeelec'] = $this->m_utils->getAllEELEC();

               $data['listafase'] = $this->m_utils->getAllFase();

               
				
				
			   // activado 20_01_2022
               $data['cmbContratoPadre'] = __buildComboContratoPadre('1');
               $data['cmbContrato'] = __buildComboContrato(1);

				$data['cmbZona1_2'] = __buildCmbZona_1_2_3();
				//Mandamos el Json al view
               $data['jsonCoordenadas']  =  $this->coordenadas_utils->getJsonCoordenadas();
			   
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbolTransporte');
               // permiso para registro individual modificar
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_REGINDPI_OBRA);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_REGINDPI_OBRA, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   $this->load->view('vf_plan_obra/v_registro_individual_pi',$data);
        	   
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLTablaCentral($listartabla){
     
        $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>Nombre Plan</th>
                            <th>Subproyecto</th>
                            <th>Central</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>fecha Inicio</th>
                            <th>fecha PrevEjecucion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';
		   																			                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->ItemPlan.'</td> 
                            <td>'.$row->Nombre.'</td> 
                            <td>'.$row->Subproyecto.'</td>                                 
                            <td>'.$row->Central.'</td>
                            <td>'.$row->Zonal.'</td>
                            <td>'.$row->EmpresaColab.'</td>
                            <td>'.$row->fechaInicio.'</td>
                            <td>'.$row->fechaPreviaEjecucion.'</td>
                            <td>'.$row->Estado.'</td>      
                                               
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    public function createPlanobraPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProy     = $this->input->post('selectProy');
            $idSubproy  = $this->input->post('selectSubproy');
            $idCentral  = $this->input->post('idCentral');
            $idzonal    = $this->input->post('selectZonal');
            $eecc       = $this->input->post('selectEmpresaColab');
            $flgTipo  		 = $this->input->post('flgTipo'); // 1: CAPEX , 2 : OPEX
			
			$aFuncional  = $this->input->post('aFuncional');
            $cuenta      = $this->input->post('cuenta');
            $ceco        = $this->input->post('ceco');
			
            $estadoplan = 1;
           
            $indicador   = $this->input->post('inputIndicador');
     
            
            $nombreplan  = $this->input->post('inputNombrePlan');
            $itemplanPE  = $this->input->post('inputItemPlanPE');
          
			$codigoSitio = $this->input->post('inputCodigoSitio');
			$pep2  		 = $this->input->post('pep2');
			$idContrato  = $this->input->post('selectContrato');
			
			$departamento_matriz  = $this->input->post('inputDepartamento');
			$provincia_matriz     = $this->input->post('inputProvincia');
			$distrito_matriz      = $this->input->post('inputDistrito');
			$nom_estacion_matriz  = $this->input->post('inputNomEstacion');
            $fase =  $this->input->post('selectFase');
            $pep1 =  $this->input->post('pep1');
            $iter =  $this->input->post('iter');
			
			$tipoEstudio = $this->input->post('tipoEstudio');
			$segmento 	 = $this->input->post('segmento');
			$servicio 	 = $this->input->post('servicio');
			$velocidad 	 = $this->input->post('velocidad');

            $cbxRequiereH = $this->input->post('cbxRequiereH');
			$idZona         = $this->input->post('zona'); // ZONA 1 , ZONA 2 , ZONA 3


            //$fase = $this->m_utils->getCodigoFase($this->m_utils->getYearActual());
			
			$fechaInicio = NULL;
			$eelec       = NULL;
			
            $this->m_planobra->deleteLogImportPlanObraSub();

            $itemplan=$this->m_planobra->generarCodigoItemPlan($idProy,$idzonal);

			if($idCentral == null || $idCentral == '') {
				throw new Exception("Error interno en la central");
			}
			
			$fechaActual = $this->m_utils->fechaActual();
			$idUsuario   = $this->session->userdata('idPersonaSession');
			
			if($idUsuario == null || $idUsuario == '') {
				throw new Exception("Sesion expirada, cargar la pagina por favor.");
			}
			
            $dataSub = $this->m_utils->getDataSubProyectoById($idSubproy);

            if($dataSub['flg_reg_item_capex_opex'] == 1) {
                $arrayPep = explode('-', $pep2);
				if(count($arrayPep) != 6){
					throw new Exception('Formato de PEP no valido: '.$pep2);
				}
				
            } else if($dataSub['flg_reg_item_capex_opex'] == 2) {
                if ($aFuncional == null || $aFuncional == '') {
                    throw new Exception('No se envió el area funcional');
                }

                if ($ceco == null || $ceco == '') {
                    throw new Exception('No se envió ceco');
                }

                // if ($cuenta == null || $cuenta == '') {
                //     throw new Exception('No se envió cuenta');
                // }
                
                if (!is_numeric($aFuncional)) {//DEBE SER NUMERICO
                    throw new Exception('el area funcional debe ser numerico');
                }

                // if (!is_numeric($ceco)) {//DEBE SER NUMERICO
                    // throw new Exception('el ceco debe ser numerico');
                // }

                // if (!is_numeric($cuenta)) {//DEBE SER NUMERICO
                //     throw new Exception('la cuenta debe ser numerico');
                // }
				
                // if ((strlen($ceco) !== 8)) {
                    // throw new Exception('El ceco no cuenta con 8 numeros : ' . $ceco);
                // }
				if($flgTipo == 1) {
					$arrayPep = explode('-', $pep2);
					if(count($arrayPep) != 6){
						throw new Exception('Formato de PEP no valido: '.$pep2);
					}
					$cantidad = $this->m_utils->existePep1enMargen(substr($pep2,0,20));
					if($cantidad == 0){
						throw new Exception('Debe ingresar una pep2 que exista en el margen.');
					}
				}

            }
			// if($idSubproy != 632) {
				// $arraySisego = explode('-', $indicador);
			
				// if(count($arraySisego) != 3) {
					// throw new Exception('El indicador no tiene el formato correcto, verificar.');
				// }
			// }
			
			
			
			$dataInsert = array(
	            "itemPlan"  => $itemplan,
				"usu_reg"   => $idUsuario,
				"fecha_reg" => $fechaActual,
	            "nombreProyecto" => strtoupper($nombreplan),
	            "indicador" => $indicador,
	            "cantidadTroba" => 0,
	            "uip" => 0,
	            "fechaInicio" =>$fechaActual,
	            "idEstadoPlan" =>intval($estadoplan),
	            "idFase" => $fase,
	            "idCentralPqt" =>intval($idCentral),
	            // "idEmpresaElec" =>intval($eelec),
	            // "idProvincia" =>intval($idProvincia),
	            // "idDepartamento" =>intval($idDepartamento),
	            "idSubProyecto" =>intval($idSubproy),
	            "idZonal" => intval($idzonal),
	            "idEmpresaColab" =>intval($eecc),
	            "itemPlanPE"=>$itemplanPE,
	            "hasAdelanto" => 0,
	            "fecha_creacion" =>$fechaActual,
				"paquetizado_fg" => 1,
				"idContrato"     => $idContrato,
				"pep2"           => $pep2,
				"ceco"           => $ceco,
				"cuenta"         => $cuenta,
				"area_funcional" => $aFuncional,
				"flg_opex"       => $flgTipo,
				"codigo_unico"   => $codigoSitio,
				"departamento_matriz" => $departamento_matriz,
				"provincia_matriz" 	  => $provincia_matriz,
                "distrito_matriz" 	  => $distrito_matriz,
				"usuario_registro"	=>	$idUsuario,
				
				"tipo_estudio"	=>	$tipoEstudio,
				"segmento"		=>	$segmento,
				"servicio"		=>	$servicio,
				"velocidad"		=>	$velocidad,
				"idZona"        => $idZona,
                "requiere_hardware" => ($cbxRequiereH == 1 ? '1' : '0')
				//"distrito_matriz" 	  => $distrito_matriz,
				//"nom_estacion_matriz" => $nom_estacion_matriz
	        );
			
            $data = $this->m_planobra_pi->insertarPlanobraPI($dataInsert,$pep1,$iter);

/*
            $time_end = microtime(true);
             $time_total = $time_end - $time_start;
*/
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar planobra');
            }else{
                $itemplanData= $this->m_planobra->obtenerUltimoRegistro();
                
                $data2 =$this->m_planobra->insertarLogPlanObra($itemplanData,$this->session->userdata('idPersonaSession'), ID_TIPO_PLANTA_INTERNA);
                if($data2['error']==EXIT_ERROR){
                    throw new Exception('Error al Insertar en el log de planobra');
                }
                $data['itemplannuevo']=$itemplanData;

                  $textmensaje='<div class="alert alert-success" role="alert">
                                Se registro el plan de obra con número : <strong>'.$itemplanData.'</strong>.
                            </div>';

                $data['notify']=utf8_decode($textmensaje);
            }
            
            //$data['listartabla'] = $this->makeHTMLTablaCentral($this->m_planobra->getAllPlanesObra());
            
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*ENLAZA CON VIEW PARA ENVIAR A MODEL Y recibir DATOS DEL ITEMPLAN PARA LA EDICION*/
  
    public function getHTMLChoiceSubProyPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProyecto = $this->input->post('proyecto');
            $listaSubProy = $this->m_utils->getAllSubProyectoByProyecto($idProyecto);
            $html = '';
            foreach($listaSubProy->result() as $row){
                $html .= '<option data-verificador="'.$row->flg_opex.'" value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getFasesSubProyPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idSubProyecto = $this->input->post('subproyecto');
            $listaSubFases = $this->m_utils->getFaseAll();
            $html = '';
            foreach($listaSubFases as $row){
                $html .= '<option  value="'.$row['idFase'].'">'.$row['faseDesc'].'</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     public function getFechaPreEjecuCalculoPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        
        try{
            $fechaInicio = $this->input->post('fecha');
            $subproy = $this->input->post('subproyecto');
          
            $fechaCalculado = $this->m_utils->getCalculoTiempoSubproyecto($fechaInicio,$subproy);
            
            $data['fechaCalculado'] = $fechaCalculado;
            $data['anioFase'] = date('Y',strtotime($fechaCalculado));

            $data['error']    = EXIT_SUCCESS;

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function getHTMLChoiceZonalPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $listaZonal = $this->m_utils->getZonalXCentralPqt($idCentral);
            $html = '';
            $idzonalselect='';
            foreach($listaZonal->result() as $row){
                $html .= '<option value="'.$row->idzonal.'">'.$row->zonalDesc.'</option>';
                $idzonalselect=$row->idzonal;
            }
            $data['listaZonal'] = $html;
            $data['idZonalSelec'] = $idzonalselect;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getHTMLChoiceEECCPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $listaEECC = $this->m_utils->getEECCXCentralPqt($idCentral);
            $html = '';
            $idEECCselect='';
            foreach($listaEECC->result() as $row){
                $html .= '<option value="'.$row->idEmpresaColab.'">'.$row->empresaColabDesc.'</option>';
                 $idEECCselect=$row->idEmpresaColab;
            }
            $data['listaEECC'] = $html;
            $data['idEECCSelec'] = $idEECCselect;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function getItemPlanSearch(){
        $itemplanPE = $this->input->post('itemplanPE');  
        $cant = null;
        if($itemplanPE != null){
            $res  = $this->m_utils->existeItemplan($itemplanPE);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;

    }
	
	function getCmbEmpresaColabByContratoPadre() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
		try {
			$idContratoPadre = $this->input->post('idContratoPadre');
			
			if($idContratoPadre == null || $idContratoPadre == '') {
				throw new Exception('Error interno no se encontro el contrato');
			}
			
			$cmbEECC = __buildCmbEECC($idContratoPadre);
		
			$data['cmbEmpresaColab'] = $cmbEECC;
			$data['error'] = EXIT_SUCCESS;
		
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getCmbDatoConfigOpex() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
		try {
			$cmbConfigOpex = __buildComboConfigOpex(1);
		
			$data['cmbConfigOpex'] = $cmbConfigOpex;
			$data['error'] = EXIT_SUCCESS;
		
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}

    public function getComboContrato(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idContratoPadre = $this->input->post('idContratoPadre') ? $this->input->post('idContratoPadre') : null;
            $idEmpresaColab = $this->input->post('idEmpresaColab') ? $this->input->post('idEmpresaColab') : null;
            
            if($idContratoPadre == null){
                throw new Exception('Error al recibir el contrato padre!!');
            }
            if($idEmpresaColab == null){
                throw new Exception('Error al recibir el proveedor!!');
            }
            $listaContrato = $this->m_utils->getContratoByIdContratoPadreEECC($idContratoPadre,$idEmpresaColab);

            $html = '<option value="">Seleccionar</option>';
            foreach($listaContrato as $row){
                $html .= '<option value="'.$row['id_contrato'].'">'.$row['contrato_marco'].'</option>';
            }
            $data['cmbContrato'] = $html;
            $data['arrayContrato'] = json_encode($listaContrato);
            $data['error']  = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getCmbContratoPadre(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
            if($idSubProyecto == null){
                throw new Exception('Error al recibir el subproyecto!!');
            }

            $dataSub = $this->m_utils->getDataSubProyectoById($idSubProyecto);

            $data['flg_reg_item_capex_opex'] = $dataSub['flg_reg_item_capex_opex'];
            $data['idTipoPlanta']            = $dataSub['idTipoPlanta'];

            $data['cmbContratoPadre'] = __buildComboContratoPadre($idSubProyecto);

            $data['codigounico'] = __buildComboContratoPadre($idSubProyecto);
            $data['error']  = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}