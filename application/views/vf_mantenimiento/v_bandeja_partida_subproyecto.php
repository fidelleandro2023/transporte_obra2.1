<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-usd,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>
        <style>

            @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }
            .select2-dropdown {
                z-index: 100000;
            }
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }

            input[type=number] { -moz-appearance:textfield; }
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>
 <?php include 'application/views/v_opciones.php';?>
            </header>

            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
                                <div class="user__name"><?php echo $this->session->userdata('usernameSession') ?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession') ?></div>
                            </div>
                        </div>


                    </div>

                    <ul class="navigation">
                    <?php echo $opciones ?>
                    </ul>
                </div>
            </aside>



            <section class="content content--full">
		                   <div class="content__inner">
                           <h2>MANTENIMIENTO DE PARTIDA SUBPROYECTO PIN</h2>
                           <hr>
		                    <div class="card" align="center">

                                <div class="card-block">
									<div class="tab-container">
										<ul class="nav nav-tabs nav-fill" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" data-toggle="tab" href="#individual" role="tab">Individual</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#masivo" role="tab">Masivo</a>
											</li>  
										</ul>
									</div>
									<div class="tab-content">
										<div class="tab-pane fade active fade show" id="individual" role="tabpanel">
											<div class="row">

												<div class="col-sm-12 col-md-8">
													<div class="form-group">
														<label>PARTIDA</label>

														<select id="selectPartida" name="selectPartida" class="select2">
															<option>&nbsp;</option>
															<?php foreach ($listaPartidas as $row) {?>
																<option value="<?php echo trim($row->idActividad) ?>"><?php echo $row->codigo.' | '.$row->descripcion ?></option>
															<?php }?>
														</select>
													</div>
												</div>

												<div class="col-sm-6 col-md-4">
													<div class="form-group">
														<label>SUBPROYECTO</label>

														<select id="selectSubProy" name="selectSubProy" class="select2">
															<option>&nbsp;</option>
															<?php foreach ($listaSubProy->result() as $row) {?>
																<option value="<?php echo trim($row->idSubProyecto) ?>"><?php echo $row->subProyectoDesc ?></option>
															<?php }?>
														</select>
													</div>
												</div>


												<div class="col-sm-6 col-md-4">
													<div class="form-group">
														<br><br>
														<button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
													</div>
												</div>

												<div class="col-sm-6 col-md-4">
													<div class="form-group">
														<label>REGISTRAR</label><br>
														<button class="btn btn-success waves-effect" type="button" onclick="openModalRegPartSubProy()">Nueva Partida-SubProy</button>
													</div>
												</div>
												<div id="contTabla" class="table-responsive">
												   <?php echo $tablaPartidaSubProy ?>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="masivo" role="tabpanel">
											<label style="font-size: smaller;text-align: left;">- La estructura del archivo debe  estar en formato .xls o .xlsx(Excel).</label><br>
											<label style="font-size: smaller;text-align: left;">- Puede descargar un ejemplo de la estructura.</label><a href="download/modelos/ejemplo_partidas.xlsx" >Aqu&iacute;</a><br>
											<label style="font-size: smaller;text-align: left;">- hacer clic en el bot&oacute;n aceptar y esperar a que llegue al 100%.</label><br>
											<label style="font-weight: bold;color: red;font-size: smaller;text-align: left;">- Debe seleccionar el proyecto y estaci&oacute;n para ingresar las partidas.</label><br>
											<div id="contProgres">
												<div class="easy-pie-chart easy-pie-tab2" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
													<span id="valuePieTab2" class="easy-pie-chart__value">0</span>
												</div>
											</div>
										<!-- <div id="contSubida"> -->
											<form method="post" id="import_form" enctype="multipart/form-data">
												<table style="margin: auto;">
													<div class="col-sm-12 col-md-10">
														<div class="col-sm-6 col-md-5">
															<div class="form-group">
																<label>SELECCIONAR SUB PROYECTO</label>

																<select id="selectProyMasivo" name="selectProyMasivo" class="select2">
																	<option value="">&nbsp;</option>
																	<?php foreach ($listaSubProy->result() as $row) {?>
																		<option value="<?php echo trim($row->idSubProyecto) ?>"><?php echo $row->subProyectoDesc ?></option>
																	<?php }?>
																</select>
															</div>
														</div>
													</div>
													<div class="container">
														<div class="form-group">
															<input type="file" name="file" id="fileExcelKit" required accept=".xls, .xlsx" onchange="subirArchivo();"/>
															<img src="" width="200"  />
														</div>
														<div>
															<tr align="center">
																<input type="submit" name="import" value="Aceptar" class="btn btn-success" />
															</tr>
														</div>
													</div>
												</table><br>
											</form> 
										</div>
									</div>
		   				        </div>
		   				    </div>
		   				    </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p>Â© Material Admin Responsive. All rights reserved.</p>

		   				                    <ul class="nav footer__nav">
		   				                        <a class="nav-link" href="#">Homepage</a>

		   				                        <a class="nav-link" href="#">Company</a>

		   				                        <a class="nav-link" href="#">Support</a>

		   				                        <a class="nav-link" href="#">News</a>

		   				                        <a class="nav-link" href="#">Contacts</a>
		   				                    </ul>
		                   </footer>
            </section>

        </main>
<!-- Small -->

                            <div class="modal fade" role="dialog" id="modalRegPartSubProy" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO PARTIDA SUBPROYECTO</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formReg" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="selectPart1">PARTIDA: </label>
                                                        <select id="selectPart1" name="selectPart1" class="select2 form-control" onchange="getSubProysByPartida()">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectSubProy1">SUBPROYECTO: </label>
                                                        <select id="selectSubProy1" name="selectSubProy1" class="select2 form-control" multiple>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSave" class="btn btn-success" onclick="savePartSubProy()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalEditPartSubProy" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModalEdit" style="margin: auto;font-weight: bold;" class="modal-title">EDITAR PARTIDA SUBPROYECTO</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formEdit" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="selectPart2">PARTIDA: </label>
                                                        <select id="selectPart2" name="selectPart2" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectSubProy2">SUBPROYECTO: </label>
                                                        <select id="selectSubProy2" name="selectSubProy2" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btnSaveEdit" class="btn btn-success" onclick="updatePartSubProy()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


        <!-- Older IE warning message -->


        <!-- Javascript -->
        <!-- ..vendors -->
        <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

   <!--  tables -->
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- Charts and maps-->
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/jqvmap.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

        <!--  -->
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script type="text/javascript">

            var idPartidaGlob = null;
            var idSubProyectoGlob = null;

            function openModalRegPartSubProy(){
                $.ajax({
                    type: 'POST',
                    url: 'getCombosPartSubProy'
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#selectPart1').html(data.cmbPartida);
                        modal('modalRegPartSubProy');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
                    }
                });
            }

            function getSubProysByPartida(){
                var idPartida = $.trim($("#selectPart1").val());
                if(idPartida != null && idPartida != '' && idPartida != undefined){
                    $.ajax({
                        type: 'POST',
                        url: 'getSubProysbyPartida',
                        data: { idPartida  : idPartida  }

                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#selectSubProy1').html(data.cmbSubProy);
                        } else {
                            mostrarNotificacion('error', 'Error', 'Hubo un error al traer los subproyectos');
                        }
                    });
                }else{
                    $('#selectSubProy1').html(null);
                }
            }




            function openEditPartSubproy(component){

                idPartidaGlob = $(component).data('idpartida');
                idSubProyectoGlob = $(component).data('idsubproyecto');

                $.ajax({
                    type: 'POST',
                    url: 'getDetPartSubProy',
                    data: { idPartida : idPartidaGlob,
                            idSubProyecto : idSubProyectoGlob}

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#selectPart2').html(data.cmbPartida);
                        $('#selectSubProy2').html(data.cmbSubProy);
                        modal('modalEditPartSubProy');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer la partida');
                    }
                });

            }

            function savePartSubProy(){

                var idSubProyecto = $.trim($("#selectSubProy1").val());
                var idPartida = $.trim($("#selectPart1").val());
                jsonValida = { idSubProyecto: idSubProyecto, idPartida: idPartida };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regPartSubProy',
                    data: {
                        idSubProyecto : idSubProyecto,
                        idPartida : idPartida
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbPartSubProy);
                        initDataTable('#data-table');
                        modal('modalRegPartSubProy');
                        mostrarNotificacion('success', 'Success', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function updatePartSubProy(){

                var idPartida = $.trim($("#selectPart2").val());
                var idSubProyecto = $.trim($("#selectSubProy2").val());

                jsonValida = { idSubProyecto: idSubProyecto, idPartida: idPartida };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                if(idPartidaGlob != null && idSubProyectoGlob != null){
                    $.ajax({
                        type: 'POST',
                        url: 'updatePartSubProy',
                        data: {
                            idPartida : idPartidaGlob,
                            idSubProyecto : idSubProyectoGlob
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbPartSubProy);
                            initDataTable('#data-table');
                            modal('modalEditPartSubProy');
                            mostrarNotificacion('success', 'Success', data.msj);
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    });
                }

            }

            function filtrarTabla(){

                var subProyecto = $.trim($('#selectSubProy').val());
                var partida = $.trim($('#selectPartida').val());

                $.ajax({
                    type	:	'POST',
                    'url'	:	'getPartSubProyByFiltros',
                    data	:	{ subProyecto : subProyecto,
                                  partida  : partida
                                }
                }).done(function(data){
                    var data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTabla').html(data.tablaPartidaSubProy);
                        initDataTable('#data-table');

                    }else{
                        mostrarNotificacion('error','Hubo problemas al filtrar los datos!!');
                    }
                });

            }
		
		$('#import_form').on('submit', function(event){
			$('.easy-pie-tab2').data('easyPieChart').update('40');
			$('#valuePieTab2').html(40);

			var input = document.getElementById('fileExcelKit');

			var idSubProyecto = $('#selectProyMasivo option:selected').val();
			var idEstacion = $('#selectEstacionMasivo option:selected').val();
			
			var form = new FormData();

			if(idSubProyecto == '' || idSubProyecto == null) {
				mostrarNotificacion('error', 'Debe seleccionar proyecto');
				event.preventDefault();
				return;
			}

			form.append('idSubProyecto' , idSubProyecto);
			form.append('file'       , input.files[0]);
			event.preventDefault();
			
			$.ajax({
				url         : 'insertPartidasMasivaPin',
				method      : 'POST',
				data        : form,
				contentType : false,
				cache       : false,
				processData : false
			}).done(function(data){
				$('.easy-pie-tab2').data('easyPieChart').update('70');
				$('#valuePieTab2').html(70);
				data = JSON.parse(data);

				if(data.error == 0) {
					$('.easy-pie-tab2').data('easyPieChart').update('100');
					$('#valuePieTab2').html(100);
					mostrarNotificacion('success', data.msj, 'correcto');
				} else {
					mostrarNotificacion('warning', data.msj);
				}
			});
		});
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>