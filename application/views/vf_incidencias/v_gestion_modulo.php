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
                           <h2>GESTION DE MODULOS</h2>
                           <hr>
		                    <div class="card">
		                        <div class="card-block">
		                          <div class="row">
                                    <button class="btn btn-success waves-effect" type="button" onclick="openModalNuevoIncidente()">REGISTRAR NUEVO MODULO</button>
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
<div class="modal fade" id="modalRegistrarIncidente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVO MODULO</h5>
            </div>
            <div class="modal-body">
                <form id="formRegistrarIncidente" method="post" class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>ID MODULO</label>
                                <input type="number" id="txtModulo" name="txtModulo" class="form-control" onchange="validarid()"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>DESCRIPCION</label>
                                <input id="txtDescripcion" name="txtDescripcion" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>COMENTARIO</label>
                                <input id="txtComentario" name="txtComentario" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>ESTADO</label>
                                <select id="selectEstado" name="selectEstado" class="select2 form-control">
                                    <option value=""></option>
                                    <option value="A">ACTIVO</option>
                                    <option value="I">INACTIVO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="mensajeForm4"></div>
                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                            <button id="btnRegistrarIncidente" type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </div>
                </form>
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

        /////////////////////////////////////////////////////////////////////////////////////////////////////////
      	function openModalNuevoIncidente(){
          	$("#txtModulo").prop('disabled', false);
      		$("#selectEstado").val("").trigger('change');
      		$('#txtModulo').val('');
      		$('#txtDescripcion').val('');
      		$('#txtComentario').val('');
      		$('#hTipoActividad').val('REGISTRAR');
      		
      		$('#formRegistrarIncidente').bootstrapValidator('resetForm', true);
      		$('#modalRegistrarIncidente').modal('toggle');
      	}

      	$('#formRegistrarIncidente')
        .bootstrapValidator({
                container: '#mensajeForm4',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                	txtModulo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir un id de modulo.</p>'
                            }
                        }
                    },
                    txtDescripcion: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir una descripcion.</p>'
                            }
                        }
                    },
                    txtComentario: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir un Comentario.</p>'
                            }
                        }
                    },
                    selectEstado: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un estado.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function (e) {
            e.preventDefault();
    
    
            var $form = $(e.target),
                formData = new FormData(),
                params = $form.serializeArray(),
                bv = $form.data('bootstrapValidator');
    
            $.each(params, function (i, val) {
                console.log('name ' + val.name + ' value ' + val.value);
                formData.append(val.name, val.value);
            });

            var hTipoActividad = $('#hTipoActividad').val();
            if(hTipoActividad == 'ACTUALIZAR'){
            	formData.append('txtModulo', $('#txtModulo').val());
            }
            formData.append('hTipoActividad', hTipoActividad);
    
            $.ajax({
                data: formData,
                url: "pqt_registrar_modulo",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#modalRegistrarIncidente').modal('toggle');
                    mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                    swal({
                      	   title: 'Se registro correctamente!',
                             text: 'Codigo de Incidente: ' + data.codigo_tipo_incidente,
                             type: 'success',
                             buttonsStyling: false,
                             confirmButtonClass: 'btn btn-primary',
                             confirmButtonText: 'OK!',
                             allowOutsideClick: false
                         }).then(function(){
                      	   location.reload();
                         }, function(dismiss) {
                      	   location.reload();
                         });
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {
    
            });
        });
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        function abrirModalInfo(component){
        	$("#txtModulo").prop('disabled', false);
        	$("#selectEstado").val("").trigger('change');
      		$('#txtModulo').val('');
      		$('#txtDescripcion').val('');
      		$('#txtComentario').val('');
      		
        	var id_modulo = $(component).data('id_modulo');
        	var descripcion = $(component).data('descripcion');
        	var comentario = $(component).data('comentario');
        	var estado = $(component).data('estado');

        	$('#hTipoActividad').val('ACTUALIZAR');
        	$("#txtModulo").prop('disabled', true);
        	$("#selectEstado").val(estado).trigger('change');
      		$('#txtModulo').val(id_modulo);
      		$('#txtDescripcion').val(descripcion);
      		$('#txtComentario').val(comentario);
    		
    		$('#modalRegistrarIncidente').modal('toggle');
        }

        function validarid(){
        	var txtTipoIncidente = $("#txtModulo").val();
        	var formData = new FormData();
            formData.append('txtModulo', txtTipoIncidente);
        	
        	$.ajax({
                data: formData,
                url: "pqt_validar_id_modulo",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    if(data.esIdValido == 0){
                        //ES INVALIDO
                    	swal({
                        	   title: 'INVALIDO!',
                               text: 'El id que intenta registrar es invalido, ya existe',
                               type: 'warning',
                               buttonsStyling: false,
                               confirmButtonClass: 'btn btn-primary',
                               confirmButtonText: 'OK!',
                               allowOutsideClick: false
                           }).then(function(){
                        	   $("#txtModulo").val('');
                        	   $('#formRegistrarIncidente').bootstrapValidator('resetForm', true);
                           }, function(dismiss) {
                        	   $("#txtModulo").val('');
                        	   $('#formRegistrarIncidente').bootstrapValidator('resetForm', true);
                           });
                    }
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
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>