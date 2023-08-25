<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/dropzone/downloads/css/dropzone.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
          <style>
                .subir{
                padding: 5px 10px;
                background: #f55d3e;
                color:#fff;
                border:0px solid #fff;    	     
                width: 40%;
                border-radius: 25px;
            }
             
            .subir:hover{
                color:#fff;
                background: #f7cb15;
            }
        </style>
    </head>

    <body data-ma-theme="entel">

        <main class="main">
            <div class="page-loader">
                <div class="page-loader__spinner">
                    <svg viewBox="25 25 50 50">
                        <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>

            <header class="header">
                <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
                    <div class="navigation-trigger__inner">
                        <i class="navigation-trigger__line"></i>
                        <i class="navigation-trigger__line"></i>
                        <i class="navigation-trigger__line"></i>
                    </div>
                </div>

                <div class="header__logo hidden-sm-down" style="text-align: center;">
                   <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
                </div>
 <?php include('application/views/v_opciones.php'); ?>
            </header>

            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
                                <div class="user__name"><?php echo $this->session->userdata('usernameSession')?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession')?></div>
                            </div>
                        </div>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

						         <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

         
            <section class="content content--full">
            	<div id='textMensaje'>
                   
                </div>
           
               <div class="content__inner">
                    <h2>FORMULARIO - <?php echo $codigo; ?></h2>                        
                    <h3><?php echo ($flg_principal == 0) ? '(SISEGO PRINCIPAL)' : '(SISEGO RESPALDO)';  ?></h3>                        
	                    
                        <div class="card">			                        
	                        <div class="card-block">
                                <form id="formAddPlanobra" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                    <div class="row">
                                         <div class="col-sm-3 col-md-3">
                                             <div class="form-group">
                                                <label>NODO PRINCIPAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="getDataSeiaMtc(1);">
                                                       <option value="">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                                <div id="mensajeNodoPrincipal"></div>
                                            </div>
                                         </div> 

                                          <div class="col-sm-3 col-md-3">
                                             <div class="form-group">
                                                <label>NODO RESPALDO</label>
                                                <select id="selectCentral2" name="selectCentral2" class="select2 form-control" onchange="getDataSeiaMtc(1);">
                                                       <option value="">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                         </div>
										 
										 <div class="col-sm-3 col-md-3">
                                             <div class="form-group">
                                                <label>&#191;EBC?</label>
                                                <select id="selecElegirEbc" name="selecElegirEbc" class="select2 form-control" onchange="getEbcByDistritoByDistrito();">
                                                    <option value="">Seleccionar SI/NO</option>
                                                    <option value="1">SI</option>
                                                    <option value="2">NO</option>                                                                
                                                </select>
                                                <div id="mensajeOptionEbc"></div>
                                            </div>
                                         </div>
										 
										 <div id="contEbcs" class="col-sm-3 col-md-3" style="display:none;">
                                             <div class="form-group">
                                                <label>SELECCIONAR EBC</label>
                                                <select id="cmbEbc" name="cmbEbc" class="select2 form-control">
                                                                                                   
                                                </select>
                                            </div>
                                         </div>
                                         
                                         <div class="col-sm-3 col-md-3" id="contFacRed">
                                            <div class="form-group has-feedback" style="">
                                                <label>FACILIDADES DE RED</label>
                                                <input id="inputFacRed" name="inputFacRed" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANTIDAD CTO</label>
                                                <input id="inputCantCTO" name="inputCantCTO" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>METROS TENDIDO A&Eacute;REO</label>
                                                <input id="inputMetroTenAereo" step="0.01" name="inputMetroTenAereo" type="number" class="form-control" onchange="getDataSeiaMtc();"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                         <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>METROS TENDIDO SUBTERRANEO</label>
                                                <input id="inputMetroTenSubt" step="0.01" name="inputMetroTenSubt" type="number" class="form-control" onchange="getDataSeiaMtc();"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>METROS NUEVA CANALIZACI&Oacute;N</label>
                                                <input id="inputMetroCana" name="inputMetroCana" type="text" class="form-control" onchange="getDiasMatriz();"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. CAMARAS NUEVAS</label>
                                                <input id="cantCamaNue" name="cantCamaNue" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. POSTES NUEVOS</label>
                                                <input id="inputPostNue" name="inputPostNue" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. POSTES DE APOYO</label>
                                                <input id="inputCantPostApo" name="inputCantPostApo" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. APERTURA DE C&Aacute;MARA</label>
                                                <input id="inputCantAperCamara" name="inputCantAperCamara" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>REQUIERE SEIA</label>
                                                <div style="background:#FEFAF9">
                                                    <select id="selectRequeSeia" name="selectRequeSeia" class="select2 form-control" onchange="getDiasMatriz();" disabled>
                                                        <option value="">Seleccionar</option>  
                                                        <option value="NO">NO</option>     
                                                        <option value="SI">SI</option>                                                    
                                                    </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>REQUIERE APROBACION MML, MTC</label>
                                                <div style="background:#FEFAF9">
                                         
													<input id="selectRequeAproMmlMtc" name="selectRequeAproMmlMtc" class="form-control" style="background:#FEFAF9" disabled>
													<div id="mensajeMtc"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>REQUIERE APROBACION INC(PMA)</label>
                                                <select id="selectRequeAprobINC" name="selectRequeAprobINC" class="select2 form-control" onchange="getDiasMatriz();">
                                                    <option value="">Seleccionar</option>  
                                                    <option value="NO">NO</option>     
                                                    <option value="SI">SI</option>                                                    
                                                </select>
                                            </div>
                                        </div>   
                                       
                                       <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>DURACI&Oacute;N (D&Iacute;AS)</label>
                                                <input id="inputDias" name="inputDias" class="form-control" style="background:#FEFAF9" disabled>
                                                <!-- <label>DURACI&Oacute;N (D&Iacute;AS)</label>
                                                <select id="cmbDuracion" name="cmbDuracion" class="select2 form-control">
                                                    <option value="">Seleccionar duracion</option>    
                                                    <option value="15">15</option>    
                                                    <option value="30">30</option>    
                                                    <option value="60">60</option>    
                                                    <option value="90">90</option>    
                                                </select> -->
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeInputDias"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>TIPO DISE&Ntilde;O</label>
                                                <select id="cmbTipoDiseno" name="cmbTipoDiseno" class="select2 form-control" onchange="setQuinceDias();">
                                                    <option value=""></option>
                                                    <?php foreach($arrayTipoDiseno AS $row) {
                                                        echo '<option value="'.$row['id_tipo_diseno'].'">'.utf8_decode($row['descripcion']).'</option>';
                                                    } ?>
                                                </select>
                                                <div id="mensajeTipoDiseno"></div>
                                            </div>    
                                        </div>

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO OBRA CIVIL</label>
                                                <input onchange="getcalculos()" id="inputCostoOc" name="inputCostoOc" type="number" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeCostoMat"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO MATERIALES</label>
                                                <input onchange="getcalculos()" id="inputCostoMat" name="inputCostoMat" type="number" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeCostoMat"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO MANO DE OBRA PQT</label>
                                                <input onchange="getcalculos()" id="inputCostMo" value="<?php echo isset($costo_pqt_mo) ? $costo_pqt_mo : NULL; ?>" name="inputCostMo" type="number" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeCostoMo"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO EXPEDIENTE EIA,CIRA,PMEA S./</label>
                                                <select id="cmbMontoEIA" name="cmbMontoEIA" class="select2 form-control" onchange="getcalculos()">
                                                    <option value="">Seleccionar monto</option>
                                                    <option value="0" selected>0</option>
                                                </select>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO ADICIONALES ZONA RURAL S./</label>
                                                <input onchange="getcalculos()" id="inputCostoAdicZona" name="inputCostoAdicZona" type="number" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeCostoAdicional"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO TOTAL S./</label>
                                                <input id="inputCostoTotal" name="inputCostoTotal" type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeCostoTotal"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>PERFIL</label>
                                                <input id="perfil" name="perfil" type="file" required accept=".pdf" class="form-control input-mask">
                                                <i class="form-group__bar"></i>
                                                <div id="mensajePerfil"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>SISEGO COTIZADO</label>
                                                <input id="sisegoCotizado" name="sisegoCotizado" type="file" required accept=".pdf" class="form-control input-mask">
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeCotizacion"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>RUTAS (KMZ)</label>
                                                <input id="rutas" name="rutas" type="file" required accept=".kmz" class="form-control input-mask">
                                                <i class="form-group__bar"></i>
                                                <div id="mensajeRutas"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-9">
                                            <div class="form-group has-feedback" style="">
                                                <label>COMENTARIO</label>
                                                <textarea id="textareaComentario" name="textareaComentario" class="form-control"></textarea>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <buttom id="idBtnArchivos" class="btn btn-success" onclick="openModalArchivos();">Subir Archivos</buttom>
                                            </div>
                                        </div>   -->
                                        <div class="col-sm-12 col-md-12" style="text-align: center;">
                                            <div id="mensajeForm"></div>
                                        </div>  
                                        <div class="col-sm-12 col-md-12" style="text-align: center;">
                                            <div class="form-group" style="text-align: center;">
                                                <div class="col-sm-12">                                      
                                                    <button data-cod="<?php echo $codigo?>" id="btnSave"  class="btn btn-primary">ENVIAR COTIZACION</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                        <div id="contTabla" class="table-responsive">
                            <?php echo $tablaHijos?>
                        </div>
                    </div>
                </div>
            </div>                          
                <footer class="footer hidden-xs-down">
                    <p>Telefonica del Peru</p>
                </footer>
                    <div class="modal fade" id="modalDataArchivo" tabindex="-1">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 id="titulo" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>                             
                                <div class="modal-body">
                                    <form id="formFiles" method="post" class="form-horizontal" enctype="multipart/form-data"> 
                                        <div class="form-group">
                                            <label>PERFIL</label>
                                            <input id="perfil" name="perfil" type="file" required accept=".pdf" class="form-control input-mask">
                                            <i class="form-group__bar"></i>
                                            <div id="mensajeTss"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>SISEGO COTIZADO</label>
                                            <input id="sisegoCotizado" name="sisegoCotizado" type="file" required accept=".pdf" class="form-control input-mask">
                                            <i class="form-group__bar"></i>
                                            <div id="mensajeExped"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>RUTAS (KMZ)</label>
                                            <input id="rutas" name="rutas" type="file" required accept=".kmz" class="form-control input-mask">
                                            <i class="form-group__bar"></i>
                                            <div id="mensajeExped"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="botonContinuar" type="submit"class="btn btn-link">Aceptar</button>
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>    
                                </div>
                            
                            </div>
                        </div>
                    </div> 
            </section>
        </main>

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>  
       
        <!-- Javascript -->
        <!-- ..vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

   <!--  tables -->
		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
              
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
		
        <script type="text/javascript">
            var flgPrincipalGlobal = <?php echo $flg_principal;?>;
			var costoPqtGlobal     = <?php echo $costo_pqt_mo; ?>;
			var flg_distancia_lineal_global = <?php echo $flg_distancia_lineal; ?>;
			
			if(flg_distancia_lineal_global == 1) {// SI ES MAYOR A 5000
				$('#inputCostMo').prop('disabled', false);
				$('#inputCostMo').val(0);
			} else {
				$('#inputCostMo').prop('disabled', true);
				$('#inputCostMo').val(costoPqtGlobal);
			}
        </script> 
        <script src="<?php echo base_url();?>public/js/js_cotizaciones/js_form_cotizacion_individual.js?v=<?php echo time();?>"></script>
    </body>


</html>