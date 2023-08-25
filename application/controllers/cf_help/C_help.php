<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_help extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
      
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
        
	    if($logedUser != null){
                $pepsub = 0;
            
               $user = $this->session->userdata('idPersonaSession');
                $zonasUser = $this->session->userdata('zonasSession');
          
               $data['listarHelp'] = $this->makeHTMLHelpPLANOBRASINFIX();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_HELP, ID_PERMISO_HIJO_HELPCARTILLA);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_help/v_help',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLHelpPLANOBRASINFIX(){
     /*siguiente id btn 23-> tiene que ser el id de la nueva cartilla que tiene que agregarse*/
        $html = '
                                 <div class="tab-container">
                                     <ul class="nav nav-tabs nav-fill" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#extract1" role="tab">DISEÑO</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract2" role="tab">SINFIX</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract3" role="tab">CRECIMIENTO VERTICAL</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract4" role="tab">PLANTA INTERNA</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract5" role="tab">GESTION VR</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract6" role="tab">GESTION ERC</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract7" role="tab">COTIZACIONES</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract8" role="tab">GESTION PO</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#extract9" role="tab">PARTIDAS</a>
                                        </li>
                                    </ul>

                                 </div>  
                                 <div class="tab-content">
                                    <div class="tab-pane active fade show" id="extract1" role="tabpanel">

                                    <table  class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>CARTILLA BANDEJA EJECUCION DISE&Ntilde;O (SISEGOS)</td>
                            <td> CARTILLA BANDEJA EJECUCION DISE&Ntilde;O</td>
                            <td>  CARTILLA CAMBIO ESTADO PLAN DE OBRA</td>
                        </tr>

                        <tr>
                            <td> <a id="btnShow1" data-id="btnShow1" data-file="Cartilla_Bandeja_Ejecuci&oacute;n_Dise&ntilde;o_(Sisegos).pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow2" data-id="btnShow2" data-file="Cartilla_Bandeja_Ejecuci&oacute;n-Dise&ntilde;o.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow7" data-id="btnShow7" data-file="Cartilla_Cambio_de_Estado_de_Item_Plan.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>

                    </tbody>
                </table>
                        
                                    </div>

                                    
                                    <div class="tab-pane fade" id="extract2" role="tabpanel">

                                    <table  class="table table-bordered">
                    <tbody>
                    
                        <tr>
                            <td>CARTILLA GEOLOCALIZACION</td>
                            <td>CARTILLA SINFIX CRECIMIENTO VERTICAL</td>
                            <td>CARTILLA SINFIX SISEGOS-MOVILES</td>
                            <td>CARTILLA SINFIX OBRAS PUBLICAS</td>
                            <td>CARTILLA MODULO DE CANCELACIONES</td>
                            <td>CARTILLA MODULO DE PARALIZACION</td>
                            <td>CARTILLA EVALUACION CLUSTER SISEGOS</td>
                        </tr>
                    
                    
                        <tr>
                            <td><a id="btnShow3" data-id="btnShow3" data-file="CARTILLA_GEOLOCALIZACI&Oacute;N.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td><a id="btnShow4" data-id="btnShow4" data-file="Cartilla_SINFIX_Crecimiento_Vertical.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow5" data-id="btnShow5" data-file="Cartilla_Sinfix_Sisegos-M&oacute;viles.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>   <a id="btnShow8" data-id="btnShow8" data-file="Cartilla_SINFIX_Obras_Publicas.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>    <a id="btnShow11" data-id="btnShow11" data-file="Cartilla_Modulo_de_Cancelaciones.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td><a id="btnShow12" data-id="btnShow12" data-file="Cartilla_Modulo_de_Paralizacion.pdf" onclick="VerPDF(this)"><img alt="Editar" height="50px" width="50px" src="public/img/iconos/pdf.png"></a></td>
                             <td><a id="btnShow29" data-id="btnShow29" data-file="cartilla_ evaluacion_ cluster_sisegos.pdf" onclick="VerPDF(this)"><img alt="Editar" height="50px" width="50px" src="public/img/iconos/pdf.png"></a></td>
                        </tr>


                    </tbody>
                     </table>

                                    </div>
                                    
                                    <div class="tab-pane fade" id="extract3" role="tabpanel">
<table  class="table table-bordered">
                    <tbody>
                        

                        <tr>
                            <td>  CARTILLA REGISTRO CV </td>
                            <td>  CARTILLA EDICION CV RESIDENCIAL</td>
                            <td>  CARTILLA EDICION CV NEGOCIO</td>
                        </tr>
                        
                        <tr>
                            <td>  <a id="btnShow10" data-id="btnShow10" data-file="Cartilla_Registro_CV.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow15" data-id="btnShow15" data-file="Cartilla_Bandeja_Editar_CV.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow27" data-id="btnShow27" data-file="cartilla_editar_cv_negocio.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>

                                            </tbody>
                     </table>

                                    </div>
                                    
                    <div class="tab-pane fade" id="extract4" role="tabpanel">
                                    
									<div class="tab-container">
										<ul class="nav nav-tabs nav-fill" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" data-toggle="tab" href="#extractA1" role="tab">TDP</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#extractA2" role="tab">EECC</a>
											</li>
										</ul>

									</div> 
									
									<div class="tab-content">
                                    <div class="tab-pane active fade show" id="extractA1" role="tabpanel">
									<table  class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>CARTILLA PLANTA INTERNA</td>
                             
                        </tr>
                        <tr>
                            <td><a id="btnShow6" data-id="btnShow6" data-file="CARTILLA PLANTA INTERNA.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>

                            </tbody>
                     </table>
									</div>
									<div class="tab-pane active fade" id="extractA2" role="tabpanel">
									<table  class="table table-bordered">
                    <tbody>
                                               
                        <tr>
                            <td>CARTILLA BANDEJA PTR RECHAZADAS PIN-EECC</td>
                            <td>CARTILLA BANDEJA DE COTIZACION PI-EECC</td>
                        </tr>
                        
                        <tr>
                            <td>  <a id="btnShow13" data-id="btnShow13" data-file="Cartilla_Bandeja_PTR_Rechazadas_PIN-EECC.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow14" data-id="btnShow14" data-file="Cartilla_Bandeja_de_Cotizacion_PI-EECC.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>
                            </tbody>
                     </table>
									</div>
									
									
									
									</div>
                                    


                            </div>
                                    
                                    
                    <!--NUEVO-->                
                                    
                                    <div class="tab-pane fade" id="extract5" role="tabpanel">
<table  class="table table-bordered">
                    <tbody>
                    
                        <tr>
                            <td>  CARTILLA SOLICITUD VR</td>
                            <td>  CARTILLA BANDEJA SOLICITUD VR</td>
                        </tr>
                        
                        <tr>
                            <td>  <a id="btnShow16" data-id="btnShow16" data-file="Cartilla_Solicitud_VR.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow22" data-id="btnShow22" data-file="cartilla_bandeja_solicitud_VR.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>
                                              

                                            </tbody>
                     </table>

                                    </div>	

<div class="tab-pane fade" id="extract6" role="tabpanel">
<table  class="table table-bordered">
                    <tbody>
                        <tr>
                             <td>  CARTILLA SOLICITUD RETIRO</td>
                             <td>  CARTILLA BOLSA PRESUPUESTO</td>
                             
                        </tr>
                        <tr>
                             <td>  <a id="btnShow17" data-id="btnShow17" data-file="Solicitud_Retiro.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow21" data-id="btnShow21" data-file="Cartilla_Bolsa_Presupuesto.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>                    

                                            </tbody>
                     </table>

                                    </div>										
									
									<div class="tab-pane fade" id="extract7" role="tabpanel">
<table  class="table table-bordered">
                    <tbody>
                        

                        <tr>
                            <td>  CARTILLA REGISTRO COTIZACION</td>
                            <td>  CARTILLA REGISTRO ITEMPLAN COTIZACION</td>
                            <td>  CARTILLA VALIDACION COTIZACION</td>
                        </tr>
                        
                        <tr>
                             <td>  <a id="btnShow18" data-id="btnShow18" data-file="Cartilla_Registro_Cotizacion.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow19" data-id="btnShow19" data-file="Cartilla_Registro_Itemplan_Cotizacion.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                             <td>  <a id="btnShow20" data-id="btnShow20" data-file="Cartilla_Validacion_Cotizacion.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                        </tr>
                                              

                                            </tbody>
                     </table>

                                    </div>	
                                    
                                    <div class="tab-pane fade" id="extract8" role="tabpanel">
                                        <table  class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>  CARTILLA CANCELACION PO</td>
                                                    <td>  CARTILLA REGISTRO INDIVIDUAL PO</td>
                                                    <td>  CARTILLA REGISTRO MASIVO PO</td>
                                                    <td>  CARTILLA SOLICITUD VR</td>
                                                    <td>  CARTILLA REGISTRO EXPEDIENTE</td>
                                                    <td>  CARTILLA VALIDAR EXPEDIENTE</td>
                                                </tr>
                                                <tr>
                                                    <td>  <a id="btnShow22" data-id="btnShow22" data-file="cartilla_cancelacion_po.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                    <td>  <a id="btnShow23" data-id="btnShow23" data-file="cartilla_registro_individual_po.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                    <td>  <a id="btnShow24" data-id="btnShow24" data-file="cartilla_registro_masivo_po.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                    <td>  <a id="btnShow25" data-id="btnShow25" data-file="cartilla_soli_vr.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                    <td>  <a id="btnShow26" data-id="btnShow26" data-file="cartilla_registro_entrega_expediente.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                    <td>  <a id="btnShow30" data-id="btnShow30" data-file="cartilla_validar_expediente.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!--nuevo-->
                                    
                                    <div class="tab-pane fade" id="extract9" role="tabpanel">
                                        <table  class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td> CARTILLA REGISTRO PARTIDAS</td>
                                                </tr>
                                                <tr>
                                                    <td>  <a id="btnShow28" data-id="btnShow28" data-file="cartilla_registro_partidas.pdf" onclick="VerPDF(this)"><img src="public/img/iconos/pdf.png" alt="Editar" width="50px" height="50px" /></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
        ';
                    
        return utf8_decode($html);
    }
    
}