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
                <div class="content__inner">
                    <h2>COTIZACI&Oacute;N INDIVIDUAL</h2>
                    <div class="card">                            
                        <div class="card-block">                                             
                            <div class="row">
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubProyecto" name="selectSubProyecto" class="select2 form-control">
                                            <option value="">&nbsp;</option>
                                            <option value="<?php echo ID_SUB_PROYECTO_EMPRESAS?>">EMPRESAS</option>
                                            <option value="<?php echo ID_SUB_PROYECTO_NEGOCIO?>">NEGOCIO</option>
                                            <option value="<?php echo ID_SUB_PROYECTO_MAYORISTA?>">MAYORISTA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>SITUACI&Oacute;N</label>
                                        <select id="selectSituacion" name="selectSituacion" class="select2 form-control">
                                            <option value="">&nbsp;</option>
                                            <option value="0">PDT COTIZACI&Oacute;N</option>
                                            <option value="1">PDT APROBACI&Oacute;N</option>
                                            <option value="4">PDT CONFIRMACI&Oacute;N</option>                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <label>EECC</label>
                                        <select id="cmbEmpresaColab" name="selectEECC" class="select2">
                                            <option value="">&nbsp;</option>
                                                <?php
                                                foreach($listaEECC->result() as $row){
                                                ?>
                                                <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                <?php }?> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">    
                                    <div class="form-group">
                                        <label>JEFATURA</label>
                                        <select id="cmbJefatura" name="selectEECC" class="select2">
                                            <option value="">&nbsp;</option>
                                                <?php
                                                foreach($listaJefatura as $row){
                                                ?>
                                                <option value="<?php echo $row['idJefatura'] ?>"><?php echo $row['descripcion'] ?></option>
                                                <?php }?> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">    
                                    <div class="form-group">
                                        <label>STATUS BANDEJA CONFIRMACI&Oacute;N</label>
                                        <select id="cmbSituacionConf" name="cmbSituacionConf" class="select2 form-control">
                                            <option value="">&nbsp;</option>
                                            <option value="1">VALIDADO</option>
                                            <option value="2">RECHAZADO</option>                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>
                            <div id="contTabla" class="table-responsive">
                                    <?php echo $tablaAsigGrafo?>
                            </div>
                        </div>
                    </div>
                 </div>
               <footer class="footer hidden-xs-down">
                    <p>Telefonica del Peru</p>                                           
               </footer>

                <div id="modalEditarEmpresaColab" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">EDITAR MDF</h5>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label style="font-weight: bold;color: var(--azul_telefonica);">MDF</label>
                                    <select id="cmbMdf" class="select2">
                                            <option value="">.:Seleccionar:.</option>                                                    
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success boton-acepto" onclick="updateEmpresaColab();">Aceptar</button>
                                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="modalobsRechazo" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">RECHAZADO</h5>
                            </div>
                            <div class="modal-body">
                                 <div class="form-group">
                                    <label>USUARIO</label>  
                                    <input id="usuarioRech" class="form-control" disabled/>
                                </div>
                                <div class="form-group">
                                    <label>FECHA</label>  
                                    <input id="fechaRechazo" class="form-control" disabled/>
                                </div>
                                <div class="form-group">
                                    <label>OBSERVACI&Oacute;N</label>                
                                    <textarea id="observacionText" class="form-control" style="height:100px;" disabled></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalRechazo" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">RECHAZAR COTIZACI&Oacute;N</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select id="cmbMotivo" class="select2">   
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Comentario</label>
                                <textarea class="form-control" id="comentario" style="height:100px"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button class="btn btn-success" onclick="rechazarCotizacionSisego();">Aceptar</button>
                        </div>      
                        </div>
                    </div>
            </section>
        </main>
        
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
         <script src="<?php echo base_url();?>public/dropzone/downloads/dropzone.min.js"></script>
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
       
        <script type="text/javascript">
        function filtrarTabla(){
        	   
            var idSubPro    = $.trim($('#selectSubProyecto').val());
        	var idSituacion = $.trim($('#selectSituacion').val());
            var idEmpresaColab = $('#cmbEmpresaColab option:selected').val();
            var idJefatura     = $('#cmbJefatura option:selected').val();
            var flgBandConf    = $('#cmbSituacionConf option:selected').val();
            $.ajax({
                type	:	'POST',
                 url	:	'filtrarCotizacionInd',
                data	:	{   
                                idSubPro       : idSubPro,
                                idSituacion    : idSituacion,
                                idEmpresaColab : idEmpresaColab,
                                idJefatura     : idJefatura,
                                flgBandConf    : flgBandConf
                            }
            })
            .done(function(data){
                var data = JSON.parse(data);
                if(data.error == 0){           	    	          	    	   
                    $('#contTabla').html(data.tablaAsigGrafo);
                    initDataTable('#data-table');           	    		
                }else if(data.error == 1){           				
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });                
        }
        
        var distritoGlobal    = null;
        var codCotizacionGlob = null;
        function openEditarMdf(btn){
            distritoGlobal    = btn.data('distrito');
            codCotizacionGlob = btn.data('codigo_coti');

            if(distritoGlobal == null || distritoGlobal == '') {
                return;
            }

            if(codCotizacionGlob == null || codCotizacionGlob == '') {
                return;
            }

            $.ajax({
                type    :   'POST',
                'url'   :   'getMdfCotizacionInd',
                // data    :   { distrito : distritoGlobal },
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       

                    $('#cmbMdf').html(data.comboMdf);
                    $('#cmbMdf').val('').trigger('chosen:updated');
                    modal('modalEditarEmpresaColab');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

        function updateEmpresaColab() {
            var idCentral = $('#cmbMdf option:selected').val();

            if(idCentral == null || idCentral == '') {
                return;
            }

            if(codCotizacionGlob == null || codCotizacionGlob == '') {
                return;
            }

            if(distritoGlobal == null || distritoGlobal == '') {
                return;
            }

            $.ajax({
                type    :   'POST',
                'url'   :   'updateEmpresaColab',
                data    :   { idCentral      : idCentral,
                              distrito       : distritoGlobal,
                              codCotizacion  : codCotizacionGlob },
                'async' :   false
            }).done(function(data){
                data = JSON.parse(data);

                if(data.error == 0) {
                    modal('modalEditarEmpresaColab');
                    $('#contTabla').html(data.tablaBanCotizacion);
                    initDataTable('#data-table');
                    mostrarNotificacion('success', 'Se actualiz&oacute; correctamente', 'correcto');
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            });
        }
        
        function openModalObservacionRech(btn) {
            $('#fechaRechazo').val(btn.data('fecha_rechazo'));
            $('#observacionText').val(btn.data('observacion'));
            $('#usuarioRech').val(btn.data('usuario'));
            modal('modalobsRechazo');
        }
        
        var codigo_cotizacionGlb = null;
        var empresaColabGlb     = null;
        function openRechazar(btn) {
            codigo_cotizacionGlb = btn.data('codigo_cotizacion');

            if(codigo_cotizacionGlb == null || codigo_cotizacionGlb == '') {
                return; 
            }
            
            $.ajax({
                type : 'POST',
                url  : 'getMotivoRechazoCotizacion'
            }).done(function(data){
                data = JSON.parse(data);
                $('#cmbMotivo').html(data.cmbMotivo);
            });
            modal('modalRechazo');
        }
        
        function rechazarCotizacionSisego() {
            if(codigo_cotizacionGlb == null || codigo_cotizacionGlb == '' ) {
                return; 
            } 

            swal({
	            title: '&#191;Est&aacute; seguro de rechazar?',
	            text: 'La cotizacion va a cambiar a estado rechazado.',
	            type: 'warning',
	            showCancelButton: true,
	            buttonsStyling: false,
	            confirmButtonClass: 'btn btn-primary',
	            confirmButtonText: 'Si, guardar los datos!',
	            cancelButtonClass: 'btn btn-secondary',
	            allowOutsideClick: false
	        }).then(function(){
                var concatMotivo = $('#cmbMotivo option:selected').val();
                var comentario   = $('#comentario').val();

                if(concatMotivo == null || concatMotivo == '' || codigo_cotizacionGlb == '' || codigo_cotizacionGlb == null){
                    return;
                }
                
                var arrayMotivo = concatMotivo.split('|');

                formData = new FormData(),
                formData.append('codigo_cluster'   , codigo_cotizacionGlb);
                formData.append('comentario'       , comentario);
                formData.append('idMotivoSisego'   , arrayMotivo[1]);
                formData.append('idMotivoPlanObra' , arrayMotivo[0]);
    		    $.ajax({
			        data: formData,
			        url: "rechazarCotizacionSisego",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	}).done(function(data) {  
                        data = JSON.parse(data);
                        console.log(data.error);
                        if(data.error == 0){
                            var codigo = data.codigo;                     
                                swal({
                                    title: 'Se rechazo correctamente',
                                    text: codigo,
                                    type: 'success',
                                    showCancelButton: false,                    	            
                                    allowOutsideClick: false
                                }).then(function(){
                                    window.location.href = "getBandejaCotizacionIndividual";
                                });
                        }else if(data.error == 1){
                            mostrarNotificacion('error',data.msj);
                        }
                    });
	        });
        }
		
		function cambiarFlgRobot(btn){
			var codigo_cotizacion = btn.data('codigo_cotizacion');

            if(codigo_cotizacion == null || codigo_cotizacion == '') {
                return; 
            }
			
			swal({
	            title: '&#191;Est&aacute; seguro de cambiar a la bandeja de la EECC?',
	            text: 'La cotizacion va a cambiar de la bandeja del robot a la contrata.',
	            type: 'warning',
	            showCancelButton: true,
	            buttonsStyling: false,
	            confirmButtonClass: 'btn btn-primary',
	            confirmButtonText: 'Si, cambiar!',
	            cancelButtonClass: 'btn btn-secondary',
	            allowOutsideClick: false
	        }).then(function(){
    		    $.ajax({
					type: 'POST',
					url: "cambiarFlgRobotCoti",
			        data: { codigo_cotizacion : codigo_cotizacion }
			  	}).done(function(data) { 
                        data = JSON.parse(data);
                        console.log(data.error);
                        if(data.error == 0){
                            var codigo = data.codigo;                     
                                swal({
                                    title: 'Se cambio a la bandeja de la contrata correctamente',
                                    text: 'correcto',
                                    type: 'success',
                                    showCancelButton: false,                    	            
                                    allowOutsideClick: false
                                }).then(function(){
                                    $('#contTabla').html(data.tablaBanCotizacion);
									initDataTable('#data-table');
                                });
                        }else if(data.error == 1){
                            mostrarNotificacion('error',data.msj);
                        }
                    });
	        });
		}
        </script>
    </body>
</html>