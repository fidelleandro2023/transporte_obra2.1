<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

   
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="UTF-8">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>PLANOBRA | MOVISTAR</title>

        <link rel="icon" type="image/png" href="<?php echo IMG_MOVISTAR_CABECERA; ?>" />


        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/loading/jquery.loading.min.css" />
        
        <!-- App styles -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">

        

        
        <style>
            .select2-dropdown {
                z-index: 100000;
            }

            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
            input[type=number] { -moz-appearance:textfield; }

   
            @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }

			.modal-backdrop	{
				opacity:0.5 !important;
			}

			.btn:not(.btn-secondary):not([class*=btn-outline-]):not(.btn-link) {/*para quitar los border del group button*/
                box-shadow: 0 0 0 0 rgba(0,0,0,.12) !important;
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
                <a href="https://www.movistar.com.pe/" title="MOSVISTAR PERU"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>

                <?php include('application/views/v_opciones.php'); ?>
            </header>
            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
								<div class="user__name"><?php echo $this->session->userdata('usernameSession')?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession')?></div>
                            </div>
                        </div>
                    </div>

                    <ul class="navigation">
                        <?php echo isset($opciones) ? $opciones : null ?>
                    </ul>
                </div>
            </aside>


            <section class="content content--full">
                <div class="content__inner">
                    <h2><?php echo $title ?></h2>
                    <br><br>
                    <div class="card">
                        <div class="card-block">

							<div class="form-group" style="border: solid 1px;" > 
							
								<div class="row" style="margin-top: 40px; margin-left: 30px; margin-right: 30px; margin-bottom: 40px;">
									<div class="col-sm-12 col-md-12" style="text-align: center;">
										<div class="form-group">
											<div class="btn-group" role="group" aria-label="Basic example">
												<button class="btn btn-success" style="margin-right: 20px;" type="button" onclick="filtrarTabla()" id="btnBuscar">Mostrar Bandeja</button>
												<img id="loadGif1" src="<?php echo base_url();?>public/img/gif/indicator.gif" style="width: 25px;height: 25px; display: none;">
											</div>
										</div>
									</div>
								</div>

							</div>
							<div id="contTabla" class="table-responsive">
									<?php echo !isset($tbReporte) ? null : $tbReporte; ?>
							</div>
							
                        </div>
                    </div>
                    
                    

                </div>
            </section>

     
         
        </main>

        <div class="modal fade" id="modalLiberarSol"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModalLiberarSol" style="margin: auto;font-weight: bold;" class="modal-title">LIBERAR SOLICITUD</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">                               
                        <div class="table-responsive" id="">
                            <div class="form-group" style="text-align: center;">
                                <label class="custom-control custom-radio">
                                    <input id="radioS" value="S" type="radio" name="radioOpcion" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">CAMBIAR PEP</span>
                                </label>

                                <label class="custom-control custom-radio">
                                    <input value="P" type="radio" name="radioOpcion" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">LIBERAR</span>
                                </label>
                            </div>
                            <div id="contInputPEP1S" class="form-group col-md-6" style="display: none;">
                                <label>PEP1</label>
                                <input id="inputP1S" name="inputP1S" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000">
                                <i class="form-group__bar"></i>
                            </div>
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="liberarSolicitud();">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="modal fade" id="modalReguEvidencia"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="titModalRegEvi" style="margin: auto;font-weight: bold;" class="modal-title">REGULARIZAR EVIDENCIA</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">                               
                        <div class="form-group col-md-6" style="text-align: center;">
							<input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false" accept=".zip,.rar">
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="regularizarEvidencia();">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
		
		<div class="modal bd-example-modal-sm" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content" style="text-align: center;background-color: #00000070;border-radius: 30%;">
					<div class="modal-header">
						<h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>                       
					</div>
					<div class="modal-body">
						<div id="contProgres">
							<div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="rgb(255, 255, 255)" data-bar-color="rgb(255, 255, 255)">
								<span id="valuePie" class="easy-pie-chart__value">0</span>
							</div>
						</div>
					</div>
					<div class="modal-footer">                       
						<h3 style="color: rgb(255, 255, 255);padding-right: 35px;">Procesando...</h3>                          
					</div>
				</div>
			</div>
		</div>

    </body>


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



    <script src="<?php echo base_url(); ?>public/jquery.numeric/jquery.numeric-min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

    <!--  -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>    

    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>


    <script>

		function filtrarTabla(){
			
            $.ajax({
                type  :	'POST',
                url   :	'getBandejaSolPdtPptRobot',
                data  :	{},
				beforeSend: () => {
					$('#loadGif1').css("display", "block");
					$('#btnBuscar').attr("disabled", true);
				}
            }).done(function(data){
				var data = JSON.parse(data);
                if(data.error == 0){
                    $('#contTabla').html(data.tbReporte);
                    initDataTable('#data-table');

                }else{
                    mostrarNotificacion('error','Aviso',data.msj);
				}

            }).always(() => {
				$('#loadGif1').css("display", "none");
				$('#btnBuscar').removeAttr("disabled");
			});
            
		}
        var itemplanGlobal = null;
        var codigoSolicitudGlobal = null;

        function openModalLiberarSol(component){
            var jsonData = $(component).data();
            console.log(jsonData);
            itemplanGlobal = jsonData.itemplan;
            codigoSolicitudGlobal = jsonData.solicitud;
            modal('modalLiberarSol');
            // $.ajax({
			// 		type  :	'POST',
			// 		url   :	'getDetalleRepTruncoPromotor',
			// 		data  :	{ 
            //             promotor: jsonData.promotor,
            //             situacion: jsonData.situacion,
            //             subestado: jsonData.substado_trunco
            //         },
			// 		beforeSend: () => {
			// 			$('body').loading({
			// 				message: 'Espere por favor...'
			// 			});
			// 		}
			// }).done(function(data){
			// 	var data = JSON.parse(data);
			// 	if(data.error == 0){
            //         $('#contRepCertPromo').html(data.htmlDetalle);
            //         initDataTable('#tableDetalle');
			// 		modal('modalDetCertPromotor');
			// 	}else{
			// 		mostrarNotificacion('error','Aviso',data.msj);
			// 	}

			// }).always(() => {
			// 	$('body').loading('destroy')
			// });
        }
        var optionRadioGlobal = null;
        $('input[type=radio][name=radioOpcion]').change(function() {
            optionRadioGlobal = this.value;
            console.log(this.value);
            if (this.value == 'S') {
                console.log('here');
            	$('#contInputPEP1S').show();
            }
            else if (this.value == 'P') {
                $('#contInputPEP1S').hide();
                $('#inputP1S').val(null);
            }
        });

        function liberarSolicitud(){
            console.log(optionRadioGlobal);
            if(optionRadioGlobal == null || optionRadioGlobal == undefined || optionRadioGlobal == ''){
                mostrarNotificacion('warning','Alerta','Debe seleccionar una acción para guardar.');
                return;
            }
            var msj = '';
            if(optionRadioGlobal == 'S'){
                var newPep1 = $('#inputP1S').val();
                if(newPep1 == null || newPep1 == undefined || newPep1 == ''){
                    mostrarNotificacion('warning','Alerta','Debe ingresar una pep1 para guardar.');
                    return;
                }
                msj = 'Está seguro de cambiar la pep1??';
            }else{
                msj = 'Está seguro de liberar la solicitud??';
            }

            swal({
                    title: msj,
                    text: 'Asegurese de validar la información',
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-success',
                    confirmButtonText: 'SI',
                    showCancelButton: true,
                    cancelButtonClass: 'btn btn-danger',
                    cancelButtonText: 'NO'

            }).then(function () {
                $.ajax({
                    type  :	'POST',
                    url   :	'liberarSolicitudRobotRPA',
                    data  :	{
                        itemplan : itemplanGlobal,
                        codigoSolicitud : codigoSolicitudGlobal,
                        newPep1: newPep1,
                        accion : optionRadioGlobal
                    },
                    beforeSend: () => {
                        $('#loadGif1').css("display", "block");
                        $('#btnBuscar').attr("disabled", true);
                    }
                }).done(function(data){
                    var data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTabla').html(data.tbReporte);
                        initDataTable('#data-table');
						modal('modalLiberarSol');
                        mostrarNotificacion('success', 'Liberacion exitosa.', data.msj);

                    }else{
                        mostrarNotificacion('error','Aviso',data.msj);
                    }

                }).always(() => {
                    $('#loadGif1').css("display", "none");
                    $('#btnBuscar').removeAttr("disabled");
                });
            }).catch(swal.noop);
        }
		
		
		function cancelarSolicitudQuiebre(btn) {
			var codigo_solicitud = btn.data('solicitud');
			var itemplan 	     = btn.data('itemplan');
			if(codigo_solicitud == null || codigo_solicitud == '') {
				return;
			}

			swal({
                    title: 'Está seguro de cancelar esta solicitud?',
                    text: 'Asegurese de validar la información',
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-success',
                    confirmButtonText: 'SI',
                    showCancelButton: true,
                    cancelButtonClass: 'btn btn-danger',
                    cancelButtonText: 'NO'

            }).then(function () {
				$.ajax({
                    type  :	'POST',
                    url   :	'cancelarSolicitudQuiebre',
                    data  :	{
								codigoSolicitud : codigo_solicitud,
								itemplan        : itemplan
							}
                }).done(function(data){
					data = JSON.parse(data);
					
					if(data.error == 0) {
						swal({
							title: 'Se canceló correctamente',
							text: 'Asegurese de validar la informacion!',
							type: 'success',
							buttonsStyling: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: 'OK!'
						}).then(function(){
							location.reload();
						});
					} else {
						mostrarNotificacion('error','Aviso',data.msj);
					}	
				});
			}).catch(swal.noop);
		}
		
		function openModalRegEvi(component){
			$('#fileTable').val(null);
            var jsonData = $(component).data();
            console.log(jsonData);
            itemplanGlobal = jsonData.itemplan;
            codigoSolicitudGlobal = jsonData.solicitud;
            modal('modalReguEvidencia');
        }
		
		function regularizarEvidencia(){
			var comprobar = ($('#fileTable').val()).length;
            if(comprobar == 0){
				mostrarNotificacion('warning','Aviso','Debe subir un archivo a procesar!!');
				return;
            }

            // var file = $('#fileTable').val()			
            // var ext = file.substring(file.lastIndexOf("."));
            
            /*swal({
                title: 'Está seguro de cargar el achivo y actualizar los datos??',
                text: 'Asegurese de validar la información!!',
                type: 'warning',
                showConfirmButton: true,
                confirmButtonClass: 'btn btn-success',
                confirmButtonText: 'SI',
                showCancelButton: true,
                cancelButtonClass: 'btn btn-danger',
                cancelButtonText: 'NO',
                allowOutsideClick: false
            }).then(function () {*/
				var formData = new FormData();
                var files = $('#fileTable')[0].files[0];
                formData.append('file', files);
                formData.append('itemplan', itemplanGlobal);
				formData.append('codigoSolicitud', codigoSolicitudGlobal);

                $.ajax({
                    type  :	'POST',
                    url   :	'reguEviQuiebrePinMasDespliegue',
                    data  :	formData,
                    contentType: false,
                    processData: false,
                    cache: false/*,
                    xhr: function() {
                        $('.easy-pie-chart').data('easyPieChart').update('0');
                        $('#valuePie').html('0');
                        modal('modalProgreso');
                        var xhr = $.ajaxSettings.xhr();
                        xhr.upload.onprogress = function(e) {
                            var progreso = Math.floor(e.loaded / e.total * 100);                                
                            $('.easy-pie-chart').data('easyPieChart').update(progreso);
                            $('#valuePie').html(progreso);
                        };
                        return xhr;
                    }*/
                }).done(function(data){
                    var data = JSON.parse(data);
					console.log(data);
                    if(data.error == 0){
                        //modal('modalProgreso');
                        swal({
                            title: 'Aviso',
                            text: data.msj,
                            type: 'success',
                            showConfirmButton: true,
                            allowOutsideClick: false
                        }).then(function () {
							location.reload();
                        });
                    }else{
                        //modal('modalProgreso');
                        mostrarNotificacion('error','Error',data.msj);
                        return;
                    }
                    
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    //modal('modalProgreso');
                    mostrarNotificacion('error','Error',errorThrown + '. Estado: ' + textStatus);
                    return;
                }).always(() => {
					modal('modalReguEvidencia');
                });
            //}).catch(swal.noop);
		}

    </script>
