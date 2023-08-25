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
            
            #divMapCoordenadas{
            	height: 450px;    
                width: 800px;  
            }
            
            #pac-input {
                    background-color: #fff;
                    font-family: Roboto;
                    font-size: 15px;
                    font-weight: 300;
                    margin-left: 12px;
                    padding: 0 11px 0 13px;
                    text-overflow: ellipsis;
                    width: 400px;
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
                <ul class="top-nav">

                    <li class="hidden-xs-down">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="zmdi zmdi-power"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
							<a href="logOut" class="dropdown-item">Cerrar Sesi&oacute;n</a>
                        </div>
                    </li>
                </ul>
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
					<h2>REGISTRO OBRA</h2>                                    
					<div class="card">		   				                        
						<div class="card-block">
							<!--
							<div>
								<a onclick="addPlanobra()" style="background-color: #0154a0; color: white;" class="btn btn-primary" >AGREGAR PLAN OBRA</a>
							</div>
							!-->
							<form id="formAddPlanobra" method="post" class="form-horizontal"> 
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label>PROYECTO</label>
											<select id="selectProy" name="selectProy" class="select2 form-control" onchange="changueProyect()" >
												<option>&nbsp;</option>
												  <?php foreach($listaProy->result() as $row){ ?> 
													<option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
													 <?php }?>
											</select>
										</div>
									</div>
									<div class="col-md-2">	  
										<div class="form-group">
											<label>SUBPROYECTO</label>
											<select id="selectSubproy" name="selectSubproy" class="select2 form-control" onchange="selectSubProyecto(1);">
												<option value="">Seleccionar</option>													
											</select>
										</div>
									</div>	

									<div class="col-md-2">	  
										<div class="form-group">
											<label>Fases</label>
											<select id="selectFase" name="selectFase" class="select2 form-control" onchange="verificar_fase()">
                                                <option value="">SELECCIONE FASE</option>       
                                                <?php foreach($listafase->result() as $row){ 
														if($row->idFase == ID_FASE_2023 ||$row->idFase == ID_FASE_2022 ||$row->idFase == ID_FASE_2021 ||$row->idFase == ID_FASE_2020 || $row->idFase == ID_FASE_2019 || $row->idFase == ID_FASE_2018 || $row->idFase == ID_FASE_2017 ){?> 
                                                    <option value="<?php echo $row->idFase?>"><?php echo $row->faseDesc ?></option>
														<?php }
													}?>                                                  
                                            </select>
										</div>
									</div>

									
									
									<div class="col-md-2">	  
										<div class="form-group">
											<label>CONTRATO PADRE</label>
											<select id="selectContratoPadre" name="selectContratoPadre" class="select2 form-control" onchange="getCmbEmpresaColabByContratoPadre()">
												<?php echo $cmbContratoPadre; ?>
												<option value="">Seleccionar</option>																								   
											</select>
										</div>
									</div>
									<div class="col-sm-12 col-md-8">
										<div class="form-group" id="divMapaCoordenadasXY">
											<label>MAPA</label>
											<input id="pac-input" class="controls col-sm-6" type="text" placeholder="Buscar">
											<div id="divMapCoordenadas" class="col-sm-12 col-md-12"></div>
										</div>
									</div>

									
									<div class="col-md-4">
										<div class="form-group col-sm-12" style="display:none">
											<label>FASE</label>
											<input id="inputFase" name="inputFase" type="text" class="form-control" readonly="true">	   
										</div>

										<div class="col-sm-12">
											<div class="form-group">
												<label>PROVEEDOR</label>
												<select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control" onchange="getCmbContratoHijo();">
													<option value="">Seleccionar</option>																							   
												</select>
											</div>
										</div>

										<div class="form-group col-sm-12">
											<label>CONTRATO MARCO</label>
											<select id="selectContrato" name="selectContrato" class="select2 form-control" onchange="getDataContrato()">
												<option value="">Seleccionar</option>																								   
											</select>
										</div>

										<div class="form-group col-sm-12" id="contPep1"  style="display:none">
										<div class="form-group">
											<label>PEP1</label>
											<input id="inputPep1" name="inputPep1" type="text" class="form-control" readonly><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
											<i class="form-group__bar"></i>
										</div>
									   </div>

										<!-- <div class="form-group col-sm-12">
											<label>CM</label>
											<input id="inputCM" name="inputCM" type="text" class="form-control" readonly><i class="form-control-feedback" data-bv-icon-for="inputCM"></i>
											<i class="form-group__bar"></i>
										</div>  -->
										<div class="form-group col-sm-12">
											<label>VIGENCIA</label>
											<input id="inputVigencia" name="inputVigencia" type="text" class="form-control" readonly><i class="form-control-feedback" data-bv-icon-for="inputVigencia"></i>
											<i class="form-group__bar"></i>
										</div>
										<div class="form-group col-sm-12">
											<label>TIPO</label>
											<input id="dato_tipo" name="dato_tipo" type="text" class="form-control" readonly><i class="form-control-feedback" ></i>
											<i class="form-group__bar"></i>
										</div>
										<div class="form-group col-sm-12">
											<label>TIPO MONEDA</label>
											<input id="inputTipoMoneda" name="inputTipoMoneda" type="text" class="form-control" readonly><i class="form-control-feedback" data-bv-icon-for="inputTipoMoneda"></i>
											<i class="form-group__bar"></i>
										</div> 
										
										<div class="form-group col-sm-12" id="contCodigoUnico">
											<label>C&Oacute;DIGO SITIO</label>
											<input id="inputCodigoSitio" name="inputCodigoSitio" type="text" class="form-control" onchange="getCoordenadasByCodigoUnico();"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
											<i class="form-group__bar"></i>
										</div>    
										
									</div>
									<div class="col-sm-4 col-md-4" id="contDepaMatriz">
										<div class="form-group">
											<label>DEPARTAMENTO</label>
											<input id="inputDepartamento" name="inputDepartamento" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4" id="contProvMatriz">
										<div class="form-group">
											<label>PROVINCIA</label>
											<input id="inputProvincia" name="inputProvincia" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4" id="contDistrMatriz">
										<div class="form-group">
											<label>DISTRITO</label>
											<input id="inputDistrito" name="inputDistrito" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4" id="contNomMatriz">
										<div class="form-group">
											<label>NOMBRE ESTACI&Oacute;N</label>
											<input id="inputNomEstacion" name="inputNomEstacion" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4">
										<div class="form-group">
											<label>ZONAL</label>
											<select id="selectZonal" name="selectZonal" class="select2 form-control" >
																									
											</select>
										</div>
									</div>
									<div class="col-sm-4 col-md-4" style="display:none">
										<div id="contInputCorreP" class="form-group has-feedback" style="">
											<label>COORDENADAS X</label>
											<input id="inputCoordX" name="inputCoordX" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
											<i class="form-group__bar"></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4">
										<div id="contInputCorreP" class="form-group has-feedback" style="">
											<label>COORDENADAS Y</label>
											<input id="inputCoordY" name="inputCoordY" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
											<i class="form-group__bar"></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4" style="display:none">
										<div id="contInputCorreP" class="form-group has-feedback" style="">
											<label>TIPO</label>
											<select id="cmbCaOpe" name="cmbCaOpe" class="select2 form-control" onchange="setTipoCaOpe();">
												<option></option>
												<option value="1">CAPEX</option>	
												<option value="2">OPEX</option>													
											</select>
										</div>
									</div>
									
									<div class="form-group col-sm-4 col-md-3" id="contCmbDatoOpex" style="display:none;">
										<label>DATOS OPEX</label>
										<select id="cmbDataOpex" name="cmbDataOpex" class="select2 form-control" onchange="setDataOpex();">
										</select>
									</div>
									<div class="form-group col-sm-4 col-md-3" id="contCeco" style="display:none;">
										<label>CECO</label>
										<input id="inputCeco" name="inputCeco" type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
										<i class="form-group__bar"></i>
									</div>
									<div class="form-group col-sm-4 col-md-3" id="contCuenta" style="display:none;">
										<label>CUENTA</label>
										<input id="inputCuenta" name="inputCuenta" type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
										<i class="form-group__bar"></i>
									</div>
									<div class="form-group col-sm-4 col-md-3" id="contAreaFuncional" style="display:none;">
										<label>AREA FUNCIONAL</label>
										<input id="inputAreaFun" name="inputAreaFun" type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
										<i class="form-group__bar"></i>
									</div>
									
									<div class="col-sm-4 col-md-4" id="contPep2" style="display:none">
										<div class="form-group">
											<label>PEP2</label>
											<input id="inputPep2" name="inputPep2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
											<i class="form-group__bar"></i>
										</div>
									</div>
								

									<div class="col-sm-4 col-md-4" id="contPep1" style="display:none">
										<div class="form-group">
											<label>Iter</label>
											<input id="inputIter" name="inputIter" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
											<i class="form-group__bar"></i>
										</div>
									</div>

									<div class="col-sm-4 col-md-4" id="contInputIndicador" style="display:none">
										<div class="form-group has-feedback">
											<label>INDICADOR</label>
											<input id="inputIndicador" name="inputIndicador" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
											<i class="form-group__bar"></i>
										</div>
									</div>
									<div class="col-sm-4 col-md-4" id="nombre_plan" style="display:none">
										<div id="contInputCorreP" class="form-group has-feedback" style="">
											<label>NOMBRE DEL PLAN</label>
											<input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
											<i class="form-group__bar"></i>
										</div>
									</div>								
								</div>
								<div id="mensajeForm"></div>  
								<div class="form-group" style="text-align: right;">
									<div class="col-sm-12">
										<button id="btnSave" type="submit" class="btn btn-primary">Guardar</button>
									</div>
								</div>
							</form>
							<!--
						   <p style="font-weight: bolder;    font-size: large;    text-align: center;">Plan de Obra 2019 concluida, coordinar con el administrador.</p>
							 -->                                              
						</div>
					</div>
				</div>
				<footer class="footer hidden-xs-down">
					<p>Telefonica del Peru.</p>
			   </footer>
            </section>
        </main>

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>
        
        <div class="modal fade" id="modalRegistrarPlanobra">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR PLAN OBRA</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddPlanobra" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <label>PROYECTO</label>
                                                <select id="selectProy" name="selectProy" class="select2 form-control" onchange="changueProyect()" >
                                                    <option>&nbsp;</option>
                                                      <?php foreach($listaProy->result() as $row){ ?> 
                                                        <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                   
                                   <div class="form-group">
                                         <label>CENTRAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral();">
                                                       <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                  
                                   
                                    <div class="form-group">
                                         <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control">
                                                                                                                                                           
                                                </select>
                                    </div>
                                    


                                    <div class="form-group" style="display:none">
                                         <label>FASE</label>
                                                <input id="inputFase" name="inputFase" type="text" class="form-control" readonly="true">
                                               
                                    </div>
                                </div>
								
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                        <label>SUBPROYECTO</label>
                                        <select id="selectSubproy" name="selectSubproy" class="select2 form-control" onchange="selectSubProyecto(2);">
                                                                                                        
                                        </select>
                                    </div>
                                      <div class="form-group">
                                         <label>ZONAL</label>
                                                <select id="selectZonal" name="selectZonal" class="select2 form-control" >
                                                                                                        
                                                </select>
                                    </div>

                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>LUGAR</label>
                                        <input id="inputIndicador" name="inputIndicador" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>


                                    
                                    
                                    <div class="form-group" style="display:none">
                                         <label>EMPRESA ELECTRICA</label>
                                                <select id="selectEmpresaEle" name="selectEmpresaEle" class="select2 form-control" >
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaeelec->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaElec ?>"><?php echo $row->empresaElecDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>               
                                </div>


                                <div class="col-sm-12 col-md-12">

                                 <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>NOMBRE DEL PLAN</label>
                                        <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                        <i class="form-group__bar"></i>
                                    </div>

                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>FECHA DE INICIO</label>
    
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                            <div class="form-group">
                                                <input id="inputFechaInicio" name="inputFechaInicio" type="text" class="form-control date-picker" placeholder="Pick a date" onchange="recalcular_fecha_prev_ejec()">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>ITEMPLAN PLANTA EXTERNA</label>
                                        <input id="inputItemPlanPE" name="inputItemPlanPE" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>FECHA PREV.EJECUCION</label>
                                        <input id="inputFechaPrev" name="inputFechaPrev" type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        <div id="mensajeForm"></div>  
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnSave" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                    </div>                    
                </div>
            </div>
        </div>

       
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
		
		function getCoordenadasByCodigoUnico() {
			var codigoUnico = $('#inputCodigoSitio').val();
			var url = 'https://www.plandeobras.com/sam/api_listar_validate_codigounico';
			var data = { "CodigoUnico" : codigoUnico };

			fetch(url, {
			  method: 'POST', // or 'PUT'
			  body: JSON.stringify(data), // data can be `string` or {object}!
			  headers:{
				'Content-Type': 'application/json'
			  }
			}).then(res => res.json())
			.catch(error => console.error('Error:', error))
			.then((response) => {

				console.log(response);
				if(response.flag == 1) {
					$('#inputCoordX').val(response.Longitud);
					$('#inputCoordY').val(response.Latitud);
					$('#inputDepartamento').val(response.Departamento);
					$('#inputProvincia').val(response.Provincia);
					$('#inputDistrito').val(response.Distrito);
					$('#inputNomEstacion').val(response.NombreEstacion);
					changeXY();
				} else {
					mostrarNotificacion('error', response.msj, 'Verificar');
				}
			});
		}
		
        /*actualizacion dinamica de combobox*/
        /*actualizacion de subproyecto a partir del proyecto*/
         var IDSUB = null;
         var itemP =null;

     
         $('#inputIndicador, #selectCentral').bind('keypress blur', function() {
        
			$('#inputNombrePlan').val($('#inputIndicador').val() + ' - ' +
							$('#selectCentral option:selected').text() );
		});


        function changueProyect(){
            var proyecto = $.trim($('#selectProy').val());
			
			if(proyecto == 49) {
				$('#contCodigoUnico').css('display', 'none');
				$('#contDepaMatriz').css('display', 'none');
				$('#contProvMatriz').css('display', 'none');
				$('#contDistrMatriz').css('display', 'none');
				$('#contNomMatriz').css('display', 'none');
				
				$('#inputCodigoSitio').val('');
				$('#inputDepartamento').val('');
				$('#inputProvincia').val('');
				$('#inputDistrito').val('');
				$('#contNomMatriz').val('');
			} else {
				$('#contCodigoUnico').css('display', 'block');
				$('#contDepaMatriz').css('display', 'block');
				$('#contProvMatriz').css('display', 'block');
				$('#contDistrMatriz').css('display', 'block');
				$('#contNomMatriz').css('display', 'block');
			}
				
				
             $.ajax({
                type    :   'POST',
                'url'   :   'getSubProPI',
                data    :   {proyecto  : proyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
					//console.log(data.listaSubProy);
                    $('#selectSubproy').html(data.listaSubProy);
                    $('#selectSubproy').val('').trigger('chosen:updated');
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

        
        function recalcular_fecha_prev_ejec(){
            var subproy = $.trim($('#selectSubproy').val()); 
           
            if(subproy==undefined || subproy=='undefined' || subproy==''){
                $('#inputFechaPrev').val('');
                return false;
            }

            var inputFechaInicio = $.trim($('#inputFechaInicio').val()); 
            
            if(inputFechaInicio==undefined || inputFechaInicio=='undefined' || inputFechaInicio==''){
                $('#inputFechaPrev').val('');
                return false;
            }

            $.ajax({
                type    :   'POST',
                'url'   :   'getFechaSubproPI',
                data    :   { fecha  : inputFechaInicio,
                              subproyecto  : subproy
                            },
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
    
                 $('#inputFechaPrev').val(data.fechaCalculado);
                 $('#inputFase').val(data.anioFase);
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al obtener la fecha de prevista!');
                }
            });
        }



        function changueCentral(){
			console.log("ENTRO IDCENTRAL: "+central);

			console.log($('#selectCentral').val());
            var central = $.trim($('#selectCentral').val());
			
             $.ajax({
                type    :   'POST',
                'url'   :   'getZonalPI',
                data    :   {central  : central},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
				console.log("idcentral"+data.listaZonal);
                if(data.error == 0){ 
                    $('#inputNombrePlan').val('');      
                    $('#inputNombrePlan').val($('#selectCentral option:selected').text());
                    $('#selectZonal').html(data.listaZonal);
                    $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
     

        // function changueEECC(){
            // var central = $.trim($('#selectCentral').val()); 
             // $.ajax({
                // type    :   'POST',
                // 'url'   :   'getEECCPI',
                // data    :   {central  : central},
                // 'async' :   false
            // })
            // .done(function(data){
                // var data    =   JSON.parse(data);
                // if(data.error == 0){       
    
                    // $('#selectEmpresaColab').html(data.listaEECC);

                    // $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');
                    
                // }else if(data.error == 1){
                    
                    // mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                // }
            // });
        // }


              

      
            function addPlanobra(){
                
                /*habilitacion campos de creacion*/
                $('#selectProy').val('').trigger('change');
           	    $('#selectSubproy').val('').trigger('change');
                $('#selectCentral').val('').trigger('change');
                $('#selectZonal').val('').trigger('change'); 
                $('#selectEmpresaColab').val('').trigger('change'); 
                $('#selectEmpresaEle').val('6').trigger('change');
                $('#inputFase').val('');
                $('#inputIndicador').val('');
                $('#inputFechaInicio').val('');
                $('#inputFechaPrev').val('');
                $('#inputNombrePlan').val('');
               
                $('#inputItemPlanPE').val('');
                
                /**/
           	    $('#formAddPlanobra').bootstrapValidator('resetForm', true); 
            	$('#modalRegistrarPlanobra').modal('toggle'); //abrirl modal        	
            }

            
            $('#formAddPlanobra')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                    selectProy: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un proyecto.</p>'
                            }
                        }
                    },
                     selectSubproy: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un subproyecto.</p>'
                            }
                        }
                    },
                    selectEmpresaColab: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una empresa colaboradora.</p>'
                            }
                        }
                    },
                                       
          	    	inputNombrePlan: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el nombre del plan.</p>'
                            }
                        }
                    },
					selectContrato: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el contrato.</p>'
                            }
                        }
                    },
					selectFase: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una fase.</p>'
                            }
                        }
                    }
        	    }
        	}).on('success.form.bv', function(e) {
        		e.preventDefault();       		
        		
        	    var $form    = $(e.target),
        	        formData = new FormData(),
        	        params   = $form.serializeArray(),
        	        bv       = $form.data('bootstrapValidator');	 
        	       
                    itemplanPE= $.trim($('#inputItemPlanPE').val()); 


                    if(itemplanPE!=""){
                        dato=existeItemPlan(itemplanPE);
                       
                        if (dato!=1){
                            $('#mensajeForm').html('<p style="color:red">(*) El itemplan ingresado no existe.</p>');
                            return false;
                        }

                    }
					
					console.log("idCentral : "+idCentralGlobal);
					if(idCentralGlobal == null || idCentralGlobal == '') {
						return;
					}
					var idZonal = $('#selectZonal option:selected').val();
					
					if(idZonal == null || idZonal == '') {
						return;
					}
					
					var idSubProyecto = $('#selectSubproy option:selected').val();
					if(idSubProyecto != 632) {
						var indicador = $('#inputIndicador').val();



						if(indicador == null || indicador == '') {
							//mostrarNotificacion('error', 'Debe Ingresar el indicador o sisego', 'verificar');
							//return;
							var indicador = $('#inputIndicador').val('0');
						}
					}
					
					var flgCapOpe = $('#cmbCaOpe option:selected').val();
					
					var ceco 	   = null;
					var cuenta 	   = null;
					var aFuncional = null;
					var pep2 = null;
					
					if(flgCapOpe == null || flgCapOpe == '') {
						mostrarNotificacion('error', 'No selecciono el tipo', 'verificar');
						return;
					}
					if(flgCapOpe == 1) {
						pep2 = $('#inputPep2').val();
						
						if(pep2 == null || pep2 == '') {
							mostrarNotificacion('error', 'No ingreso la PEP2', 'verificar');
							return;
						}
						
						formData.append('pep2', pep2);
					} else if(flgCapOpe == 2) {
						ceco = $('#inputCeco').val();
						cuenta = $('#inputCuenta').val();
						aFuncional = $('#inputAreaFun').val();
						
						if(ceco == null || ceco == '') {
							mostrarNotificacion('error', 'No ingreso el ceco', 'verificar');
							return;
						}
						
						if(cuenta == null || cuenta == '') {
							mostrarNotificacion('error', 'No ingreso la cuenta', 'verificar');
							return;
						}
						
						if(aFuncional == null || aFuncional == '') {
							mostrarNotificacion('error', 'No ingreso el area funcional', 'verificar');
							return;
						}
						
						formData.append('aFuncional', aFuncional);
						formData.append('cuenta', cuenta);
						formData.append('ceco', ceco);
					}
        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });
					
					var proyecto = $.trim($('#selectProy').val());

					var pep1 = $('#inputPep1').val();
					var iter = $('#inputIter').val();
					
					if(proyecto == 49) {			
						var codigoSitio = $('#inputCodigoSitio').val();
						
						if(codigoSitio ==  null || codigoSitio == '') {
							mostrarNotificacion('error','Verificar', 'Debe ingresar el codigo Unico');
							return;
						}
					}
        		    
					formData.append('idCentral', idCentralGlobal);

					
					formData.append('flgTipo', flgCapOpe);
					formData.append('pep1', pep1);
					formData.append('iter', iter);

					console.log($('#inputNombrePlan').val("----"));
        		    $.ajax({
    			        data: formData,
    			        url: "addPlanobraPI",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    	data = JSON.parse(data);
    				    	if(data.error == 0){
								var itemplan=data.itemplannuevo;
								swal({
										type: 'success',
										title: 'Se registro el nuevo plan de obra con numero '+itemplan+' correctamente!',
										text: 'Registro correcto',
										showConfirmButton: true,
										backdrop: false,
										allowOutsideClick: false,
										allowEscapeKey: false
									}).then(() => {
										 $('#textMensaje').html(data.notify);
										location.reload();
									});								
                               


    				    		// mostrarNotificacion('success','Operacion exitosa.', '');
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Verificar', data.msj);
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});
      
                   
			function existeItemPlan(itemplan){
				var result = $.ajax({
					type : "POST",
					'url' : 'getItemPlanSearch',
					data : {
						'itemplanPE' : itemplan
					},
					'async' : false
				}).responseText;
		   
				return result;
			}
			
			function getCmbEmpresaColabByContratoPadre() {
				var idContratoPadre = $('#selectContratoPadre option:selected').val();
				if(idContratoPadre == null || idContratoPadre == '' || idContratoPadre == undefined){
					$('#selectContrato').html('<option value="">Seleccionar</option>');
					$('#selectEmpresaColab').html('<option value="">Seleccionar</option>');
				}else{
					$.ajax({
						type : 'POST',
						url  : 'getCmbEmpresaColabByContratoPadre',
						data : { idContratoPadre : idContratoPadre }
					}).done(function(data){
						data = JSON.parse(data);
						if(data.error == 0) {
							$('#selectEmpresaColab').html(data.cmbEmpresaColab);
						} else {
							mostrarNotificacion('error', data.msj, 'Comunicarse con la persona a cargo');
						}
					});
				}
			}

			var arrayContratoGlob = [];

			function getCmbContratoHijo() {
				var idContratoPadre = $('#selectContratoPadre option:selected').val();
				var idEmpresaColab = $('#selectEmpresaColab option:selected').val();
				arrayContratoGlob = [];
				$('#inputVigencia').val(null);
				$('#inputTipoMoneda').val(null);

				if(idEmpresaColab == null || idEmpresaColab == '' || idEmpresaColab == undefined){
					$('#selectContrato').html('<option value="">Seleccionar</option>');
				}else{
					$.ajax({
						type : 'POST',
						url  : 'getCmbContratoHijoByCPEECC',
						data : { 
							idContratoPadre : idContratoPadre,
							idEmpresaColab : idEmpresaColab
						}
					}).done(function(data){
						data = JSON.parse(data);
						if(data.error == 0) {
							$('#selectContrato').html(data.cmbContrato);
							arrayContratoGlob = JSON.parse(data.arrayContrato);
							console.log(arrayContratoGlob);

						} else {
							mostrarNotificacion('error', data.msj, 'Comunicarse con la persona a cargo');
						}
					});
				}
			}
			
			function getDataContrato() {
				var idContrato = $('#selectContrato option:selected').val();
				if(idContrato == null || idContrato == '' || idContrato == undefined){
					// $('#inputCM').val(null);
					$('#inputVigencia').val(null);
					$('#inputTipoMoneda').val(null);
				}else{
					for (var x = 0; x < arrayContratoGlob.length; x++) {
						if(arrayContratoGlob[x]['id_contrato'] == idContrato){
							arrayContratoGlob[x]['flg_asignacion'] = 1;
							// $('#inputCM').val(arrayContratoGlob[x]['contrato_marco']);
							$('#inputVigencia').val(arrayContratoGlob[x]['vigencia']);
							$('#inputTipoMoneda').val(arrayContratoGlob[x]['tipo_moneda']);
							return false;
						}
					}
				}
			}

		
		/************METODOS GOOGLE MAP**************/
            var marker = null;
            var map = null;
            var center = null;
            
            function init(){
                
                // var mapdivMap = document.getElementById("contenedor_mapa");                
                // center = new google.maps.LatLng(-12.0431800, -77.0282400);
                // var myOptions = {
                //     zoom: 5,
                //     center: center,
                //     mapTypeId: google.maps.MapTypeId.ROADMAP
                // }
                // map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);
                infoWindow = new google.maps.InfoWindow();
                // marker = new google.maps.Marker({
                //     map: map,
                //     title:"Tu posicion",
                //     draggable: true,
                //     animation: google.maps.Animation.DROP
                //   });

                // var geocoder = new google.maps.Geocoder();
                // google.maps.event.addListener(marker, 'dragend', function(){
                // 	var pos = marker.getPosition();
                // 	geocoder.geocode({'latLng': pos}, function(results, status) {
                //    		 if (status == google.maps.GeocoderStatus.OK) {                			
                //       			llenarTextosByCoordenadas(results,pos)
                //     			var address=results[0]['formatted_address'];
                //    			 	openInfoWindowAddress(address,marker);				
            	// 		 }
    			//  	});		
      			//   	map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
        		// });

            //     google.maps.event.addListener(map, 'click', function(event) {
            // 		marker.setMap(null);
            		
            // 	    marker = new google.maps.Marker({
            //         position: event.latLng,
            //         map: map,
            //         title:"Tu posiciÃ³n",
            //         draggable: true,
            //         animation: google.maps.Animation.DROP
            //       });

            // 	    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            //        		 if (status == google.maps.GeocoderStatus.OK) {                    			
            //         			var pos = marker.getPosition();
            //           			llenarTextosByCoordenadas(results,pos)
            //         			var address=results[0]['formatted_address'];
            //        			 	openInfoWindowAddress(address,marker);
            // 			 }
      		//  		});	
            // 	    var pos = marker.getPosition();
            // 	    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng())); 
            	    
            //       google.maps.event.addListener(marker, 'dragend', function(){
            //         	var pos = marker.getPosition();
            //         	geocoder.geocode({'latLng': pos}, function(results, status) {
            //            		 if (status == google.maps.GeocoderStatus.OK) {                        			
            //               			llenarTextosByCoordenadas(results,pos)
            //             			var address=results[0]['formatted_address'];
            //            			 	openInfoWindowAddress(address,marker);				
            //     			 }
        	// 		 	});
          	// 		  	map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
            // 		});
            // }); 
            }

            function searchDireccion(){
         	 	 address = document.getElementById('search').value;
          	 if(address!=''){
          		 if(isCoordenada(address)){
          			 buscarPorCoordenadas(address);
          		 }else{//ES DIRECCION
          			 console.log('address:'+address);
  	        		 var geocoder = new google.maps.Geocoder();
  	            	geocoder.geocode({ 'address': address}, function(results, status){
  	       			   if (status == 'OK'){
  	           			  console.log('..-'+JSON.stringify(results[0].geometry.location));
  	          			// Posicionamos el marcador en las coordenadas obtenidas
  	       				 
  	       				// Centramos el mapa en las coordenadas obtenidas
  	       				// map.setCenter(marker.getPosition());
  	       				map.setCenter(results[0].geometry.location);
  	  	       			map.setZoom(16);
    	       			 

  	       				marker.setPosition(results[0].geometry.location);
  	       				
  	          				var address	=	results[0]['formatted_address'];
  	           			 	openInfoWindowAddress(address,marker);	
  	
  	              			 geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
  	                       		 if (status == google.maps.GeocoderStatus.OK) { 
  	                        			llenarTextosByCoordenadas(results,marker.getPosition());                   			
  	                        			//console.log('searchDireccion:'+JSON.stringify(results));
  	                			 }
  	          		 		});
  	            		 		
  	    				 }
  	   			 	})   
          		 }
          	 }  	 
          }

            function isCoordenada(cadena){
            	var str = cadena;
                var res = str.split(',');
                
                if(res.length == 2){
                	var x = res[0].trim();
                    var y = res[1].trim();
                	
                    var valid_x = (x.match(/^-?\d+(?:\.\d+)?$/));
                    var valid_y = (y.match(/^-?\d+(?:\.\d+)?$/));
                    
                    if(valid_x){
                    	if(valid_y){
                    		return true;
                    	}else{
                    		return false;
                    	}
                    }else{
                    	return false;
                    }            	
                }else{
                	return false;
                }
                           
            }

            function buscarPorCoordenadas(cadena){
	        	var str = cadena;
	            var res = str.split(',');
	            var x = res[0].trim();
                var y = res[1].trim();
                
      			map.setCenter(new google.maps.LatLng(x, y));   
      			map.setZoom(16);
  				marker.setPosition(new google.maps.LatLng(x, y)); 				
          			 	
  			 	var geocoder = new google.maps.Geocoder();
  			 	geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
              		 if (status == google.maps.GeocoderStatus.OK) { 
              			var address	=	results[0]['formatted_address'];
          			 	openInfoWindowAddress(address,marker);	
               			llenarTextosByCoordenadas(results,marker.getPosition());                   			
               			//console.log('searchDireccion:'+JSON.stringify(results));
              		 }
 		 		});  			    
       	 
  			}

            function openInfoWindowAddress(Addres,marker) {
                console.log('geo..');
                 infoWindow.setContent([
                 	Addres
                 ].join(''));
                 infoWindow.open(map, marker);
             }

            /***************************************************
    		METODO PARA LLENAR CAMPOS POR LAS COORDENADAS
        ****************************************************/
		var idCentralGlobal = null;
         function llenarTextosByCoordenadas(results,pos){
             console.log(results[1]['address_components'][4].long_name.toUpperCase());
        	try{
        		$('#txt_departamento').val(results[1]['address_components'][4].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_departamento').val('');
        	}
        	
        	try{
        		$('#txt_provincia').val(results[1]['address_components'][3].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_provincia').val('');
        	}
        	
        	try{
        		$('#txt_distrito').val(results[1]['address_components'][2].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_distrito').val('');
        	}

        	$('#inputCoordX').val(pos.lng());
        	$('#inputCoordY').val(pos.lat());
    	
         }

         function changeXY(){
			 console.log("ENTRO23333");
        	 var myLat = document.getElementById("inputCoordY").value;
        	 var myLong = document.getElementById("inputCoordX").value;
        	 
        	 if(!isNaN(myLat) && !isNaN(myLong) && myLat!= '' && myLong!= ''){
            	 $("#divMapaCoordenadasXY").show();
            	 
        		 var coordes = new google.maps.LatLng(myLat, myLong);
            	 console.log("ENTRO2");
            	 var mapOptions = {
            		center: coordes,
            		mapTypeId: google.maps.MapTypeId.ROADMAP
            	 }
            	 
            	 var mapCoor = new google.maps.Map(document.getElementById("divMapCoordenadas"), mapOptions);
            	 mapCoor.setZoom(15);
            	 var marker = new google.maps.Marker({map:mapCoor, position:coordes});
				 
            	 myLatLng = [$("#inputCoordY").val(),$("#inputCoordX").val()];

				// var codigoCentral = getBuscarArea(myLatLng);
  				// console.log(codigoCentral);
				
				// if(codigoCentral == null) {
					// return;
				// }
				
				var formData = new FormData();
				console.log("ENTRO4");
				// formData.append('codigoCentral', codigoCentral);
				formData.append('latitud' , $("#inputCoordY").val());
				formData.append('longitud', $("#inputCoordX").val());
				console.log("ENTRO5");
				$.ajax({
					data: formData,
					url: "pqt_obtCentralPorCodigo",
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST'
				}).done(function(data) {  
					data = JSON.parse(data);
					if(data.error == 0){console.log("ENTRO6654");
						idCentralGlobal = data.idCentral;
						$('#selectCentral').val(data.idCentral).trigger('change');
						$('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
						changueCentral();
						console.log(data.idCentral);
					}else if(data.error == 1){
						mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
					}
				});
  	

  		// 		if(codigoCentral != null){
  		// 			var formData = new FormData();
  		//             formData.append('codigoCentral', codigoCentral);
		// 			formData.append('latitud' , myLat);
		// 			formData.append('longitud', myLong);
  		//             $.ajax({
      	// 		        data: formData,
      	// 		        url: "pqt_obtCentralPorCodigo",
      	// 		        cache: false,
      	// 	            contentType: false,
      	// 	            processData: false,
      	// 	            type: 'POST'
      	// 		  	})
      	// 			  .done(function(data) {  
      	// 				    	data = JSON.parse(data);
      	// 			    	if(data.error == 0){
      				    		
        //                           $('#selectCentral').val(data.idCentral).trigger('change');
      	// 	                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
        //                           changueCentral();
        //                           console.log("ENTRO1");
        //                           console.log(data.idCentral);
      	// 					}else if(data.error == 1){
      	// 						mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
      	// 					}
      	// 		  	  })
      	// 		  	  .fail(function(jqXHR, textStatus, errorThrown) {
      	// 		  		mostrarNotificacion('error','Error','Comuniquese con alguna persona a cargo :');
      	// 		  	  })
      	// 		  	  .always(function() {
      			      	 
      	// 		  	});
      	// 		}else{console.log("ENTRO2222");
      	// 			$('#selectCentral').val('').trigger('change');
 	    //                 $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
 	    //                 changueCentral();
        //   			alert("No hay codigo para el area seleccionada");
      	// 		}
            	 
            }
        	 
        }

         function getBuscarArea(arrayCordenadas) {
     		var i_selec = null;
     		for(i=0; i < JSON_COORDENADAS['features'].length; i++) {
     			result = matBuscarArea(arrayCordenadas, JSON_COORDENADAS['features'][i]['geometry']['coordinates'][0][0]);
     			if(result == true) {
     				i_selec = i;
     				break;
     			}
     		}
			
     		if(i_selec != null) {
     			cod_nod = JSON_COORDENADAS['features'][i_selec]['properties']['MDF'];
     		} else {
     			cod_nod = null;
     		}
			
			if(cod_nod == null || cod_nod == '') {
				mostrarNotificacion('error', 'No se encontro codigo de central en estas coordenadas', 'Verificar');
				return;
			}
     		console.log("getBuscarArea.cod_nod: |" + cod_nod.trim() + "|");
     		return cod_nod.trim();
     	}

     	function matBuscarArea(point, vs) {
     		var x = point[0], y = point[1];
     		var inside = false;
     		for (var i = 0, j = vs.length - 1; i < vs.length; j = i++) {
     			var xi = vs[i][0], yi = vs[i][1];var xj = vs[j][0], yj = vs[j][1];
     			var intersect = ((yi > y) != (yj > y))&& (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
     			if (intersect) inside = !inside;
     		}
     		return inside;
     	}

      	var JSON_COORDENADAS   =  <?php echo $jsonCoordenadas?>;
         $( document ).ready(function() {
     	   console.log( "ready!" );
      	  var geocoder = new google.maps.Geocoder();
     	  var map;
          	 var latitude = -12.0965634; // YOUR LATITUDE VALUE
             var longitude = -77.0276785; // YOUR LONGITUDE VALUE


             if (navigator.geolocation) {
                 navigator.geolocation.getCurrentPosition(function(position) {
                	 latitude = position.coords.latitude;
                	 longitude = position.coords.longitude;
                 });
             }
             
             var myLatLng = {lat: latitude, lng: longitude};
             
             map = new google.maps.Map(document.getElementById('divMapCoordenadas'), {
               center: myLatLng,
               zoom: 14
             });

             // Create the search box and link it to the UI element.
             var input = document.getElementById('pac-input');
             var searchBox = new google.maps.places.SearchBox(input);
             map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

             // Bias the SearchBox results towards current map's viewport.
             map.addListener('bounds_changed', function() {
               searchBox.setBounds(map.getBounds());
             });

             // Listen for the event fired when the user selects a prediction and retrieve
             // more details for that place.
             searchBox.addListener('places_changed', function() {
               var places = searchBox.getPlaces();

               if (places.length == 0) {
                 return;
               }

               // For each place, get the icon, name and location.
               var bounds = new google.maps.LatLngBounds();
               places.forEach(function(place) {
                 
                 if (place.geometry.viewport) {
                   // Only geocodes have viewport.
                   bounds.union(place.geometry.viewport);
                 } else {
                   bounds.extend(place.geometry.location);
                 }
               });
               map.fitBounds(bounds);
             });
             
             // Update lat/long value of div when anywhere in the map is clicked    
             google.maps.event.addListener(map,'click',function(event) {
    				myLatLng = [event.latLng.lng(),event.latLng.lat()];

    				$("#inputCoordX").val(event.latLng.lat());
    				$("#inputCoordY").val(event.latLng.lng());

    				console.log('Ejecutar getBuscarArea...');
    				// var codigoCentral = getBuscarArea(myLatLng);
    				// console.log("COD_CENTRAL: "+codigoCentral);

    				// if(codigoCentral != null){
    					var formData = new FormData();
    		            // formData.append('codigoCentral', codigoCentral);
						formData.append('latitud' , event.latLng.lat());
						formData.append('longitud', event.latLng.lng());
    		            $.ajax({
        			        data: formData,
        			        url: "pqt_obtCentralPorCodigo",
        			        cache: false,
        		            contentType: false,
        		            processData: false,
        		            type: 'POST'
        			  	})
        				  .done(function(data) {  
        					    	data = JSON.parse(data);
        				    	if(data.error == 0){
        				    		idCentralGlobal = data.idCentral;
                                    $('#selectCentral').val(data.idCentral).trigger('change');
        		                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
                                    changueCentral();
                                    console.log(data.idCentral);
        						}else if(data.error == 1){
        							mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
        						}
        			  	  })
        			  	  .fail(function(jqXHR, textStatus, errorThrown) {
        			  		mostrarNotificacion('error','Error','Comuniquese con alguna persona a cargo :');
        			  	  })
        			  	  .always(function() {
        			      	 
        			  	});
        			// }else{
        				// $('#selectCentral').val('').trigger('change');
	                    // $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
	                    // changueCentral();
            			// alert("No hay codigo para el area seleccionada");
        			// }
    				
             });
             
             var marker;
             
             // Create new marker on double click event on the map
            google.maps.event.addListener(map,'click',function(event) {
                if ( marker ) {
                    marker.setPosition(event.latLng);
                }else{
                    marker = new google.maps.Marker({
                        position: event.latLng, 
                        map: map, 
                        title: event.latLng.lat()+', '+event.latLng.lng(),
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });
                }
                
                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {                    			
                    			var pos = marker.getPosition();
                      			llenarTextosByCoordenadas(results,pos)
                    			var address=results[0]['formatted_address'];
                   			 	openInfoWindowAddress(address,marker);
            			 }
      		 		});	
            	    var pos = marker.getPosition();
            	    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng())); 
            	    
                google.maps.event.addListener(marker, 'dragend', function(){
                    var pos = marker.getPosition();
                    geocoder.geocode({'latLng': pos}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {                        			
                                llenarTextosByCoordenadas(results,pos)
                                var address=results[0]['formatted_address'];
                                openInfoWindowAddress(address,marker);				
                            }
                    });
                    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
                });
    				      
            });
     	});
		
		function selectSubProyecto(flg) {


			
			var idSubProyecto = $('#selectSubproy option:selected').val();
			var idproyecto = $('#selectProy option:selected').val();

			var esopex=$('#selectSubproy option:selected').data('verificador');



			if(esopex==2){
				capturar_datos_opex(idSubProyecto);
				
				$("#cmbCaOpe").val('2');
				$('#dato_tipo').val("OPEX");

			}else{

				   // verificacion bolsa pep
                 capturar_datos_bolsapep(idSubProyecto,idproyecto);
				$("#cmbCaOpe").val('1');
				$('#dato_tipo').val("CAPEX");
				//$('#contPep1').css('display', 'inherit');
				
			

			}
			

			var text_idProyecto = $('#selectProy option:selected').text();
			//alert(text_idSubProyecto);
			var cap = text_idProyecto.search("SISEGO-CAPEX");
			var ope = text_idProyecto.search("SISEGO-OPEX");
			//var busqueda=cap+ope;
            evaluarCapex(cap,ope);	
			//console.log("id: "+idSubProyecto);
           // alert(idSubProyecto);
			

			

			if(idSubProyecto == 632) {
				console.log("ENTRO AL CSS");
				//datos para iniciar indicador
				//$('#contInputIndicador').css('display', 'none');
				$('#inputIndicador').val('');
			} else {
				console.log("NO ENTRO AL CSS");
				//datos para iniciar indicador
				//$('#contInputIndicador').css('display', 'block');
			}

			if(flg == 1){
				if(idSubProyecto == null || idSubProyecto == '' || idSubProyecto == undefined){
					$('#selectContratoPadre').html('<option value="">Seleccionar</option>');
				}else{
					$.ajax({
						type : 'POST',
						url  : 'getCmbContratoPadreBySubProy',
						data : {
							idSubProyecto : idSubProyecto
						}
					}).done(function(data){
						data = JSON.parse(data);
						if(data.error == 0) {
							$('#selectContratoPadre').html(data.cmbContratoPadre);
						} else {
							mostrarNotificacion('error', data.msj, 'Comunicarse con la persona a cargo');
						}
					});
				}
			}
		}


		function capturar_datos_opex(idsubproyecto){

			console.log("trio");

			$.ajax({
						type : 'POST',
						url  : 'ajaxCombinatoriaOpex',
						data : { idsubproyecto :idsubproyecto }
					}).done(function(data){
						data = JSON.parse(data);
						console.log(data);
						  
						$('#inputCeco').val(data[0].ceco);
			              $('#inputCuenta').val(data[0].cuenta);
			              $('#inputAreaFun').val(data[0].areaFuncional);
						  $('#contInputIndicador').val("0");
					});
		}

		function verificar_fase(){


		/*	var Proyecto = $('#selectProy option:selected').val();
			var idSubProyecto = $('#selectSubproy option:selected').val();
			var Pep1=$('#inputPep1').val();
			var fase_selec = $('#selectFase option:selected').val();
            console.log("validar fase subproyecto"+idSubProyecto);
			console.log("validar fase proyecto"+Proyecto);
			console.log("validar fase pep1"+Pep1);
			console.log("validar fase subproyecto"+fase_selec);
			*/

			/*
			$.ajax({
			type : 'POST',
			url  : 'ajaxBolsapepOpex',
			data : { fase_selec :fase_selec }
		    }).done(function(data){
			data = JSON.parse(data);
			console.log(data);
			
				//$('#inputPep2').val(data[0].pep2);
				

		     });
			 */

		}



		


		
		function verificar_correlativo(pep1){
			

        $.ajax({
			type : 'POST',
			url  : 'ajaxCorrelativoPEP',
			data : { pep1:pep1 }
		}).done(function(data){
			data = JSON.parse(data);
			console.log(data);
			var correlativo=armadoCorrelativo(data[0].pep1,data[0].correlativo+1);
			$('#inputPep2').val(correlativo);

			$('#inputPep1').val(data[0].pep1);

			$('#inputIter').val(data[0].correlativo);

			console.log("Pep2 correlativo:"+correlativo);
		});

		}

		function armadoCorrelativo(pep1,num){
			var count=3;
			var numZeropad = num + '';
            while(numZeropad.length < count) {
            numZeropad = "0" + numZeropad;
            }
			return pep1+'-'+numZeropad;
		}
		


		 function capturar_datos_bolsapep(idsubproyecto,idproyecto){

		
			pep1="";
			pep2="";

        $.ajax({
			type : 'POST',
			url  : 'ajaxBolsapepOpex',
			data : { idsubproyecto :idsubproyecto,idproyecto:idproyecto,pep1:pep1,pep2:pep2 }
		}).done(function(data){
			data = JSON.parse(data);
			console.log(data);
			$('#inputPep1').val(data[0].pep1);
			/* ROCESO DE CATURA PEP2
			if((data[0].pep2)!="") {
                console.log("pep 2 manual "+data[0].pep2);
				$('#inputPep2').val(data[0].pep2);
				


			} else {
				verificar_correlativo(data[0].pep1);
		
			}*/
		});
        }
		
		function setTipoCaOpe() {
			var flgCapOpe = $('#cmbCaOpe option:selected').val();
			if(flgCapOpe == 1) {
				$('#contPep2').css('display', 'block');
				
				$('#contAreaFuncional').css('display', 'none');
				$('#contCuenta').css('display', 'none');
				$('#contCeco').css('display', 'none');
				$('#contCmbDatoOpex').css('display', 'none');
				
				$('#cmbDataOpex').html(null);
				$('#inputCeco').val(null);
				$('#inputCuenta').val(null);
				$('#inputAreaFun').val(null);
			} else if(flgCapOpe == 2) {
				$.ajax({
					type : 'POST',
					url  : 'getCmbDatoConfigOpex'
				}).done(function(data) {
					data = JSON.parse(data);
					$('#cmbDataOpex').html(data.cmbConfigOpex);
					$('#inputPep2').val(null);
					$('#contPep2').css('display', 'none');
					$('#contAreaFuncional').css('display', 'block');
					$('#contCuenta').css('display', 'block');
					$('#contCeco').css('display', 'block');
					$('#contCmbDatoOpex').css('display', 'block');
				});
			}
		}
		
		function setDataOpex() {
			var cmbText = $('#cmbDataOpex option:selected').text();
			
			var arrayOpex = cmbText.split("-");
			
			$('#inputCeco').val(arrayOpex[0].trim());
			$('#inputCuenta').val(arrayOpex[1].trim());
			$('#inputAreaFun').val(arrayOpex[2].trim());
		}

		function evaluarCapex(cap,ope){

			if(cap>=0||ope>=0){
				//alert("existe capex"+n);

				$('#nombre_plan').css('display', 'inherit');
				$('#contInputIndicador').css('display', 'inherit');
				if(ope>=0){
					$('#contPep2').css('display', 'none');
					$('#inputPep2').val("P-0000-00-0000-00000-001");

				}else{
                   $('#contPep2').css('display', 'inherit');
				}



			}else{
                //alert("no existe caex"+n);
				$('#contInputIndicador').css('display', 'none');
                $('#contInputIndicador').val("0");
				$('#inputNombrePlan').val("----");
				$('#inputPep2').val("P-0000-00-0000-00000-001");
				$('#contPep2').css('display', 'none');
				$('#nombre_plan').css('display', 'none');

			}


		}




///////////////////////////////////////////////////////////////////////////////////////////////////
        </script>    
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&libraries=places&callback=init"></script>
       
        
    </body>


</html>