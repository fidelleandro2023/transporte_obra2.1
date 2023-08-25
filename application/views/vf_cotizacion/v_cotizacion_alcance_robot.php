<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="public/img/iconos/iconfinder_movistar.png">
        
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
            
            #divMapCoordenadas{
            	height: 450px;    
                width: 800px;  
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
                        
                    </ul>
                </div>
            </aside>

         
            <section class="content content--full">           
                <div class="content__inner">
                    <h2>ROBOT COTIZACI&Oacute;N</h2>                                   
                    <div class="card">			                        
                        <div class="card-block">
                            <div class="tab-container tab-container--green">
                                <ul class="nav nav-tabs nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab_modif_anul" role="tab">COSTOS PAQUETIZADOS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab_simu" role="tab">SIMULADOR</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active fade show" id="tab_modif_anul" role="tabpanel">
										<div class="row">
											<div class="form-group col-md-3">
												<label>SUBPROYECTO</label>
												<select id="cmbSubCosto" name="cmbSubCosto" class="form-control select2" onchange="getFiltrarCostoPaquetizadoSimu();">
													<?php echo isset($cmbSubProyecto) ? $cmbSubProyecto : $cmbSubProyecto; ?>
												<select>
											</div>
											<div class="form-group col-md-3">
												<label>EECC</label>
												<select id="cmbEmpCosto" name="cmbEmpCosto" class="form-control select2" onchange="getFiltrarCostoPaquetizadoSimu();">
													<?php echo isset($cmbEmpresa) ? $cmbEmpresa : $cmbEmpresa; ?>
												<select>
											</div>
											<div class="form-group col-md-3">
												<label>ESTACI&Oacute;N</label>
												<select id="cmbEstaCosto" name="cmbEstaCosto" class="form-control select2" onchange="getFiltrarCostoPaquetizadoSimu();">
													<?php echo isset($cmbEstacion) ? $cmbEstacion : $cmbEstacion; ?>
												<select>
											</div>
											<div class="form-group col-md-3">
												<label>TIPO</label>
												<select id="cmbJefatura" name="cmbJefatura" class="form-control select2" onchange="getFiltrarCostoPaquetizadoSimu();">
													<option value="LIMA">LIMA</option>
													<option value="PROVINCIA">PROVINCIA</option>
												<select>
											</div>
										</div>
                                        <div id="contTablaCostoPqt" class="table-responsive">
                                            <?php echo !isset($tablaCostoxAlcance) ? '' : $tablaCostoxAlcance; ?>
                                        </div>
										
                                    </div>
                                    <div class="tab-pane fade" id="tab_simu" role="tabpanel">
                                        <div class="table-responsive">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 row">
                                                    <div class="form-group col-sm-4 col-md-4">
                                                        <label>C&Oacute;DIGO COTIZACI&Oacute;N</label>
                                                        <input id="inputCotizacion" name="inputCotizacion" type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group col-sm-2 col-md-2">
                                                        <button class="btn btn-info" onclick="getDataByCodigoCotizacion();">consultar</button>
                                                    </div>
                                                    <div class="form-group col-sm-2 col-md-2">
                                                        <label>MDF</label>
                                                        <input id="inputNodo" name="inputNodo" type="text" class="form-control" readonly>
                                                    </div>
                                                    <div class="form-group col-sm-2 col-md-2">
                                                        <label>TIPO</label>
                                                        <input id="inputTipo" name="inputTipo" type="text" class="form-control" readonly>
                                                    </div>
                                                </div>  
                                                <div class="col-sm-12 col-md-4">
                                                    <div class="form-group">
                                                        <label>NOMBRE DEL PLAN</label>
                                                        <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control" readonly>
                                                        <div id="mensajeNombrePlan"></div>
                                                    </div>    
                                                </div>    
                                                <div class="col-sm-6 col-md-4">
                                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                        <label>COORDENADAS X</label>
                                                        <input id="inputCoordX1" name="inputCoordX" type="text" class="form-control" onchange="changeXY()" readonly><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                    </div>                                    
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                            <label>COORDENADAS Y</label>
                                                            <input id="inputCoordY1" name="inputCoordY" type="text" class="form-control" onchange="changeXY()" readonly><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                            <i class="form-group__bar"></i>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-8">
													<div class="progress">
														<div id="barra_prog" class="progress-bar" role="progressbar" style="display:none;"></div>
													</div>
                                                    <div class="form-group" id="divMapaCoordenadasXY">
                                                        <label>MAPA</label>
                                                        <input id="pac-input" class="controls col-sm-6" type="text" placeholder="Buscar">
                                                        <div id="divMapCoordenadas" class="col-sm-12 col-md-12"></div>
                                                    </div>
													<div class="row">
														<div class="checkbox col-md-4" style="color:red">
															<input type="checkbox" value="" onchange="getCtosNormalSimu();">
															<label style="color:red;">VER CTOS</label>
														</div>
														<div class="checkbox  col-md-4" style="color:red">
															<input type="checkbox" value="" onchange="getReservaSimu();">
															<label style="color:red;">VER RESERVAS</label>
														</div>
														<div class="checkbox  col-md-4" style="color:red">
															<input type="checkbox" value="" onchange="getEbcSimu();">
															<label style="color:red;">VER EBCS</label>
														</div>
														<div class="checkbox  col-md-4" style="color:red">
															<input id="checkLogRobot" type="checkbox" value="" onchange="getLogicaRobot();">
															<label style="color:red;">ACT. LOGICA COTIZACI&Oacute;N</label>
														</div>
													</div>
													
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group col-sm-12">
                                                        <label>DIRECCI&Oacute;N</label>
                                                        <input id="inputDireccion1" name="inputDireccion1" type="text" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>CLASIFICACI&Oacute;N</label>
                                                            <select id="clasificacion" name="clasificacion" class="form-control select2">
                                                                <option value="">Seleccionar</option>
                                                                <option value="NAP EXPRESS AMARILLO">NAP EXPRESS AMARILLO</option>
                                                                <option value="NAP EXPRESS VERDE">NAP EXPRESS VERDE</option>
                                                                <option value="ESTUDIO ESPECIAL GRIS">ESTUDIO ESPECIAL GRIS</option>
                                                                <option value="TRASLADO INTERNO">TRASLADO INTERNO</option>
                                                                <option value="ESTUDIO DE CAMPO">ESTUDIO DE CAMPO</option>
                                                                <option value="HABILITACION">HABILITACION</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>TIPO CLIENTE</label>
                                                            <select id="tipo_cliente" name="tipo_cliente" class="form-control select2">
                                                                <option value="">Seleccionar</option>
                                                                <option value="Edificio Monocliente">EDIFICIO MONOCLIENTE</option>
                                                                <option value="Centro Comercial">CENTRO COMERCIAL</option>
                                                                <option value="Multicliente">MULTICLIENTE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>MDF KMZ</label>
                                                            <input id="inputNodoKmz" name="inputNodoKmz" type="text" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>KMZ SITIOS ARQUEOL&Oacute;GICOS</label>
                                                            <select id="kmz_arque" name="kmz_arque" class="form-control select2" onchange="getSitiosArqueologicos();">
                                                                <option value="">Seleccionar</option>
																<option value="1">SI</option>
                                                                <option value="2">NO</option>
                                                            <select>
                                                        </div>
                                                    </div>
													<div>
														<div class="form-group">
                                                            <label>SUBPROYECTO</label>
                                                            <select id="cmbSubSimu" name="cmbSubSimu" class="form-control select2">
                                                                <?php echo isset($cmbSubProyecto) ? $cmbSubProyecto : $cmbSubProyecto; ?>
                                                            <select>
                                                        </div>
													</div>													
                                                </div>
                                                <!-- <div class="col-md-4">
                                                    <div class="form-group col-sm-3">
                                                        <label>DIRECCI&Oacute;N</label>
                                                        <input id="inputDireccion1" name="inputDireccion1" type="text" class="form-control" readonly>
                                                    </div>
                                                </div> -->
                                                <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                    <div class="form-group" style="text-align: right;">
                                                        <div class="col-sm-6">                                      
                                                            <button type="button" class="btn btn-primary" onclick="getDataSimulacion();">Generar Simulacion</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>

                <div id="modalSimulacion" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-slg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="">Simulaci&oacute;n</h3>
								<h3 id="subtituloDistancia"></h3>
                            </div>
                            <div class="modal-body">
								<div class="panel panel-default card-view">
									<div class="panel-heading">
										<h3 class="panel-title txt-dark form-group"><u>DATOS SIMULACI&Oacute;N</u></h3>
									</div>
									<div class="panel-body">
										<div class="row form-group">
											<div class="col-md-2">
												<label>NODO</label>
												<input id="nodoPrincipal" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>CTO</label>
												<input id="inputCto" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>METROS TENDIDO A&Eacute;REO</label>
												<input id="metTendAereo" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>FAC. DE RED</label>
												<input id="fac_red" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>TIPO</label>
												<input id="tipo" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-2">
												<label>H. DISP.</label>
												<input id="h_disponible" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>REQUIERE EIA</label>
												<input id="seia" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>REQUIERE APROBACION MML MTC</label>
												<input id="mtc" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>REQUIERE APROBACION INC</label>
												<input id="inc" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>TIPO DISE&Ntilde;O</label>
												<input id="tipo_diseno" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-2">
												<label>METRO OC</label>
												<input id="metro_oc_input" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>CR X-A</label>
												<input id="crxa_input" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>CR X-C</label>
												<input id="crxc_input" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>POSTES</label>
												<input id="postes_input" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>EECC</label>
												<input id="eeccsimu" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-2">
												<label>DURACI&Oacute;N</label>
												<input id="duracion" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>COSTO MO</label>
												<input id="costoMo" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>COSTO MAT</label>
												<input id="costoMat" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>COSTO EIA</label>
												<input id="costoEia" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>COSTO INC</label>
												<input id="costoInc" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-2">
												<label>COSTO MAT EDIFICIO</label>
												<input id="costo_mat_edif" class="form-control" disabled/>
											</div>
											<div class="col-md-2">
												<label>COSTO OC EDIFICIO</label>
												<input id="costo_oc_edif" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>TOTAL</label>
												<input id="total" class="form-control" disabled/>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default card-view">
									<div class="panel-heading">
										<div class="pull-left">
											<h3 class="panel-title txt-dark form-group"><u>DATOS COTIZACI&Oacute;N</u></h3>
										</div>
									</div>
									<div class="panel-body">
										<div class="row form-group">
											<div class="col-md-3">
												<label>NODO</label>
												<input id="nodoPrincipal2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>FAC. DE RED</label>
												<input id="fac_red2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>CTO</label>
												<input id="inputCto2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>METROS TENDIDO SUBTERRANEO</label>
												<input id="metTendSub2" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-3">
												<label>METROS TENDIDO A&Eacute;REO</label>
												<input id="metTendAereo2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>REQUIERE EIA</label>
												<input id="seia2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>REQUIERE APROBACION MML MTC</label>
												<input id="mtc2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>REQUIERE APROBACION INC</label>
												<input id="inc2" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-3">
												<label>TIPO DISE&Ntilde;O</label>
												<input id="tipo_diseno2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>DURACI&Oacute;N</label>
												<input id="duracion2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>COSTO MO</label>
												<input id="costoMo2" class="form-control" disabled/>
											</div>
										</div>
										<div class="row form-group">
											<div class="col-md-3">
												<label>COSTO MAT</label>
												<input id="costoMat2" class="form-control" disabled/>
											</div>
											<div class="col-md-3">
												<label>TOTAL</label>
												<input id="total2" class="form-control" disabled/>
											</div>
										</div>
									</div>
								</div>
                            </div>
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-success boton-acepto" onclick="updateEmpresaColab();">Aceptar</button> -->
                                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>                       
                <footer class="footer hidden-xs-down">
                    <p>Telef&oacute;nica del Per&uacute;</p>
                </footer>
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
        <script src="<?php echo base_url();?>public/js/js_cotizaciones/js_cotizacion_alcance_robot.js?v=<?php echo time();?>"></script>
		<script type="text/javascript">
    		var global_icon_url_cto = null;
            
			var marcadores_cto_edif   =  <?php echo json_encode($marcadores_cto_edif)?>;
            var info_markers_cto_edif =  <?php echo json_encode($info_markers_cto_edif)?>;		
			
			var marcadores_mdf      =  <?php echo json_encode($marcadores_mdf)?>;
			var info_markers_mdf    =  <?php echo json_encode($info_markers_mdf)?>;
			var global_icon_url_mdf = '<?php echo base_url();?>public/img/iconos/edificio3.png';
		</script>
		
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&libraries=places&callback=init"></script>
	</body>
</html>