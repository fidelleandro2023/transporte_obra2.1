<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
        
<!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
    
    <style type="text/css">

        .select2-dropdown {
            z-index: 100000;
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
                   <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
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

                       
                    </div>

                    <ul class="navigation">
                    <?php echo $opciones?>
                    </ul>
                </div>
            </aside>



            <section class="content content--full">
		                   <div class="content__inner">
                           <h2>ASIGNACION DE RESPONSABLES - MODULOS</h2>
                           <hr>
		                    <div class="card">
		                        <div class="card-block">
		                          <div class="row">
                                    <button class="btn btn-success waves-effect" type="button" onclick="openModalNuevoIncidente()">ASIGNAR NUEVO MODULO A RESPONSABLE</button>
                                  </div>
    		   				      <div id="divTabla" class="table-responsive">
    		   				        <?php echo $tbIncidencias?>
                                  </div>
		   				        </div>
		   				    </div>
		   				   </div>
            </section>
        </main>
            
            <!-- REGISTRAR TIPO DE INCIDENTE -->
<div class="modal fade" id="modalRegistrarIncidente" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">ASIGNAR NUEVO MODULO A RESPONSABLE</h5>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>RESPONSABLE</label>
                                <select id="selectResponsable" name="selectResponsable" class="select2 form-control" onchange="changeResponsable()">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>MODULOS</label>
                                <select multiple="multiple" id="selectModulo" name="selectModulo" class="select2 form-control">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>ESTADO</label>
                                <select id="selectEstado" name="selectEstado" class="select2 form-control">
                                    <option value=""></option>
                                    <option value="A">ACTIVO</option>
                                    <option value="I">INACTIVO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success waves-effect" onclick="agregarModulo()">Agregar</button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <div id="divModulo" class="table-responsive">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="mensajeForm4"></div>
                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-white" data-dismiss="modal" onclick="cerrarFormulario()">Cerrar</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="hTipoActividad" name="hTipoActividad" value="" />
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


        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- Charts and maps-->
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/jqvmap.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/js/js_planobra/jsConsulta.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
         <script src="https://www.w3schools.com/lib/w3.js"></script>
        <script type="text/javascript">
        $( document ).ready(function() {
      	});

        function limpiarDatos(){
        	$("#selectResponsable").prop('enabled', true);
        	$("#selectResponsable").empty();
      		$("#selectModulo").empty();
      		$("#divModulo").empty();
      		$("#selectEstado").val("").trigger('change');
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        function changeResponsable(){
        	var tempResponsable = $("#selectResponsable").val();
        	limpiarDatos();
        	var formData = new FormData();
        	console.log("changeResponsable.tempResponsable " + tempResponsable);
      		formData.append('idUsuario', tempResponsable);
        	$.ajax({
            	data : formData,
                url: "pqt_arm_candidatos_a_responsables",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#selectResponsable").append(data.html);
                    changeModulos();
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
            });
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        function changeModulos(){
        	$("#selectModulo").empty();
      		$("#divModulo").empty();
      		$("#selectEstado").val("").trigger('change');
      		var idUsuario = $("#selectResponsable").val();
      		console.log("changeModulos.idUsuario: " + idUsuario);
      		var formData = new FormData();
      		formData.append('idUsuario', idUsuario);
        	$.ajax({
            	data:formData,
                url: "pqt_arm_modulos",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#selectModulo").append(data.html);
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
            });
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
      	function openModalNuevoIncidente(){
      		limpiarDatos();
      		$('#hTipoActividad').val('REGISTRAR');
      		
      		$.ajax({
                url: "pqt_arm_candidatos_a_responsables",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#selectResponsable").append(data.html);
                    changeModulos();
              		$('#modalRegistrarIncidente').modal('toggle');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
            });
      	}
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        function abrirModalInfo(component){
        	limpiarDatos();
        	$('#hTipoActividad').val('ACTUALIZAR');
        	$("#selectResponsable").prop('disabled', true);

        	var id_responsable = $(component).data('id_responsable');
        	var nombre = $(component).data('nombre');
        	var usuario = $(component).data('usuario');
        	$("#selectResponsable").append("<option value='"+id_responsable+"'>"+nombre+"("+usuario+")"+"</option");
        	
        	changeModulos();
        	mostrarTabla();
    		$('#modalRegistrarIncidente').modal('toggle');
        }

        function cerrarFormulario(){
        	location.reload();
        }

        function mostrarTabla(){
        	$("#divModulo").empty();
        	var idUsuario = $("#selectResponsable").val();
        	var formData = new FormData();
      		formData.append('idUsuario', idUsuario);
        	$.ajax({
            	data: formData,
                url: "pqt_arm_modulos_asignados",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                	$("#divModulo").html(data.html);
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
            });
        }

        function agregarModulo(){
            var modulos = $("#selectModulo").val();
        	var idUsuario = $("#selectResponsable").val();
        	var estado = $("#selectEstado").val();

        	console.log("   modulos " + modulos);
        	console.log(" idUsuario " + idUsuario);
        	console.log("    estado " + estado);

        	if(estado == '' || modulos == '' || idUsuario == ''){
        		swal({
    	            title: 'DATOS VACIOS',
    	            text: 'Debe de llenar todos los campos!',
    	            type: 'warning',
    	            buttonsStyling: false,
    	            confirmButtonClass: 'btn btn-primary',
    	            confirmButtonText: 'OK!',
    	            allowOutsideClick: false
    	        });
    	        return;
        	}
        	
        	var formData = new FormData();
      		formData.append('modulos', modulos);
      		formData.append('idUsuario', idUsuario);
      		formData.append('estado', estado);
      		$.ajax({
            	data: formData,
                url: "pqt_arm_asignar_modulos",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {

                	swal({
        	            title: 'REGISTRO EXITOSO',
        	            text: 'Se asignaron correctamente los modulos al responsable seleccionado.',
        	            type: 'success',
        	            buttonsStyling: false,
        	            confirmButtonClass: 'btn btn-primary',
        	            confirmButtonText: 'OK!',
        	            allowOutsideClick: false
        	        }).then(function(){
        	        	$("#selectEstado").val("").trigger('change');
                    	changeModulos();
                    	mostrarTabla();
        			  	  });
                    
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
            });
        }

        function actualizarEstado(component){
        	var idUsuario = $(component).data('id_responsable');
        	var modulo = $(component).data('id_modulo');
        	var estado = $(component).data('estado');
        	
        	var formData = new FormData();
      		formData.append('idUsuario', idUsuario);
      		formData.append('modulo', modulo);
      		formData.append('estado', estado);
      		$.ajax({
            	data: formData,
                url: "pqt_arm_cambiar_estado_responsable_modulo",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {

                	swal({
        	            title: 'ACTUALIZACION EXITOSA',
        	            text: 'Se actualizo correctamente el estado del modulo seleccionado.',
        	            type: 'success',
        	            buttonsStyling: false,
        	            confirmButtonClass: 'btn btn-primary',
        	            confirmButtonText: 'OK!',
        	            allowOutsideClick: false
        	        }).then(function(){
                    	mostrarTabla();
        			  	  });
                    
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
            });
        }
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>