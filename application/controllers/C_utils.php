<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_utils extends CI_Controller {

function __construct(){
		parent::__construct();
		$this->load->model('mf_utils/m_utils');
		$this->load->library('lib_utils');
	}

	public function validateRegPoByCostoUnitario(){
	    $data['error']         = EXIT_ERROR;
	    $data['canGenSoli']    = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	        
	        $idUsuario     = ($this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null);
	        if($idUsuario  !=  null){
				
				$origen        = ($this->input->post('origen')      ? $this->input->post('origen')      : null);//1= CREACION PO MAT, 2 = CREACION PO MO, 3 = GESTION VR MAT, 4 = LIQUIDACION MO
    	        $tipoPo        = ($this->input->post('tipo_po')      ? $this->input->post('tipo_po')      : null);//1 = material, 2 = mo	        
    	        $tipoAccion    = ($this->input->post('accion')       ? $this->input->post('accion')       : null);//1 = nuevo, 2 = editar
    	        $codigo_po     = ($this->input->post('codigo_po')    ? $this->input->post('codigo_po')    : null);	        
    	        $itemplan      = ($this->input->post('itemplan')     ? $this->input->post('itemplan')     : null);
        	    $costoTotalPo  = ($this->input->post('costoTotalPo') ? $this->input->post('costoTotalPo') : null);
        	    
        	    $isSisego = $this->m_utils->isSisego($itemplan);
				if($isSisego > 0){  //SI ES SISEGO VALIDAMOS 18.03.2020 SOLO SISEGOS TENDRA BANDEJA DE EXCESOS POR EL MOMENTO      	    
						$infoCU = $this->m_utils->getVariablesCostoUnitario($itemplan, $tipoPo, (($tipoAccion == 1 ) ? null : $codigo_po));
						
						if($tipoPo == TIPO_PO_MATERIAL){
						   $costoUnitarioObra = $infoCU['costo_unitario_mat'];//costo limite de la obra
						   $desc_tipoPo = 'Material';
						}else if($tipoPo == TIPO_PO_MANO_OBRA){
							$costoUnitarioObra = $infoCU['costo_unitario_mo'];//costo limite de la obra
							$desc_tipoPo = 'Mano de Obra';
						}else{
							throw new Exception('No se pudo determinar el tipo de PO a procesar. refresque y vuelva a intentarlo, de continuar comuniquise con el Administrador.');
						}
									
						if($costoUnitarioObra==null || $costoUnitarioObra==0){
							throw new Exception('La Obra no cuenta con Costo Unitario Registrado.');
						}
						
						$hasSolActivo = $this->m_utils->hasSolExceActivo($itemplan, $tipoPo);
						if($hasSolActivo > 0){
							throw new Exception('No se pueden aplicar los cambios, debido ah que cuenta con una Solicitud de Exceso Pendiente de Aprobacion.');
						}
						
						$costoTotalAllPo    =  $infoCU['total'];//costo actual de todas las po    	   
						_log("COSTO TOTAL: ".$costoTotalAllPo." COSTO2: ".$costoTotalPo);
						$nuevoCostoTotalAllPo = $costoTotalAllPo + $costoTotalPo;
						/*
						log_message('error', '$$costoTotalTodasPo:'.$costoTotalAllPo);
						log_message('error', '$$costoTotalMat:'.$costoTotalPo);
						log_message('error', '$nuevoCostoTotalPo:'.$nuevoCostoTotalAllPo);
						log_message('error', '$costoUnitarioObra:'.$costoUnitarioObra);
						*/
						

						if($nuevoCostoTotalAllPo > $costoUnitarioObra){// si el nuevo costo es mayor al programado
							$exceso = $nuevoCostoTotalAllPo - $costoUnitarioObra;
							$data['canGenSoli']    =  EXIT_SUCCESS;//SI PODRA GENERAR SOLICITUD DE EXCEDENTE
							$data['costo_actual']  = $costoUnitarioObra;
							$data['excedente']      = $exceso;
							$data['costo_final']   = ($costoUnitarioObra+$exceso);
							//throw new Exception('No se puede procesar la Solicitud debido ah que el Costo programado para '.$desc_tipoPo.' de la Obra es de: S/.'.number_format($costoUnitarioObra,2,'.', ',').' y el costo consumido a la fecha es de:  S/.'.number_format($costoTotalAllPo,2,'.', ',').', siendo el Costo de la PO a procesar de: S/.'.number_format($costoTotalPo,2,'.', ',').' esta genera un Exceso de: S/.'.number_format($exceso,2,'.', ',').' ¿Desea Generar una Solicitud de Ampliacion de Costo de Obra por: S/.'.number_format($exceso,2,'.', ',').' ?');
							throw new Exception('No se puede registrar la po de '.$desc_tipoPo.' debido ah que excede en  S/.'.number_format($exceso,2,'.', ',').'.<br> Monto Cotizado MAT: S/.'.number_format($costoUnitarioObra,2,'.', ',').'<br> Monto Consumido:  S/.'.number_format($costoTotalAllPo,2,'.', ',').'<br> Nueva Solicitud: S/.'.number_format($costoTotalPo,2,'.', ',').'<br> Exceso: S/.'.number_format($exceso,2,'.', ',').'<br> Desea Generar una Solicitud de Ampliacion de Costo de Obra por: S/.'.number_format($exceso,2,'.', ',').' ?');
						}else{//Permitir creacion de PO        	        
							$data['error'] = EXIT_SUCCESS;
						}
				}else{
					$data['error'] = EXIT_SUCCESS;
				}					
	        }else{
	            throw new Exception('su sesion ha expirado, vuelva a iniciar sesion.');
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
        echo json_encode(array_map('utf8_encode', $data));
	}
}