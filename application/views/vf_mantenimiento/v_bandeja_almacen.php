<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                           <h2>MANTENIMIENTO DE ALMACEN</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
                                <div class="card-block"> 
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>REGISTRAR ALMACEN</label><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="openModalRegAlmacen()">Nuevo Almacen</button>
                                            </div>
                                        </div>
                                
                                    </div>
		   				            <div id="contTabla" class="table-responsive">
								        <?php echo $tablaAlmacen?>
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

                            <div class="modal fade" role="dialog" id="modalRegAlmacen" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE ALMACEN</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegPart" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="codCentro">CODIGO CENTRO: </label>
                                                        <input id="codCentro" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="codAlmacen">CODIGO ALMACEN: </label>
                                                        <input id="codAlmacen" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idJefatura">JEFATURA: </label>
                                                        <select id="idJefatura" name="idJefatura" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idEECC">EE.CC: </label>
                                                        <select id="idEECC" name="idEECC" class="select2 form-control">
                                                        </select>
                                                    </div>

                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveMat" class="btn btn-success" onclick="saveAlmacen()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalEditAlmacen" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModalEdit" style="margin: auto;font-weight: bold;" class="modal-title">EDITAR MATERIAL </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formEditMat" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="codCentro2">CODIGO CENTRO: </label>
                                                        <input id="codCentro2" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="codAlmacen2">CODIGO ALMACEN: </label>
                                                        <input id="codAlmacen2" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idJefatura2">JEFATURA: </label>
                                                        <select id="idJefatura2" name="idJefatura" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idEECC2">EE.CC: </label>
                                                        <select id="idEECC2" name="idEECC" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btnSaveEdit" class="btn btn-success" onclick="updateAlmacen()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>





        <!-- Older IE warning message -->
        

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
        
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script type="text/javascript">

            var idJefaturaGlob = null;
            var idEECCGlob = null;

            function openModalRegAlmacen(){
                $("#codCentro").val(null);
                $("#codAlmacen").val(null);

                $.ajax({
                    type: 'POST',
                    url: 'getCmbRegAlma'
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#idJefatura').html(data.cmbJefatura);
                        $('#idEECC').html(data.cmbEECC);
                        modal('modalRegAlmacen');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
                    }
                });
            }


            function openEditAlmacen(component){
                idJefaturaGlob = $(component).data('idjefatura');
                idEECCGlob = $(component).data('ideecc');
                $.ajax({
                    type: 'POST',
                    url: 'getDetAlmacen',
                    data: { idJefatura : idJefaturaGlob,
                            idEECC : idEECCGlob}
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#codAlmacen2').val(data.codAlmacen);
                        $('#codCentro2').val(data.codCentro);
                        $('#idJefatura2').html(data.cmbJefatura);
                        $('#idEECC2').html(data.cmbEECC);
                        modal('modalEditAlmacen');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer el almacen');
                    }
                });

            }


            function saveAlmacen(){

                var codCentro = $.trim($("#codCentro").val());
                var codAlmacen = $.trim($("#codAlmacen").val());
                var idJefatura = $.trim($("#idJefatura").val());
                var idEECC = $.trim($("#idEECC").val());

                jsonValida = { codCentro: codCentro, codAlmacen: codAlmacen, idJefatura: idJefatura, idEECC: idEECC };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regAlmacen',
                    data: {
                        codCentro : codCentro,
                        codAlmacen : codAlmacen,
                        idJefatura : idJefatura,
                        idEECC : idEECC
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbAlmacen);
                        initDataTable('#data-table');
                        modal('modalRegAlmacen');
                        mostrarNotificacion('success', 'Success', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function updateAlmacen(){
                var codCentro = $.trim($("#codCentro2").val());
                var codAlmacen = $.trim($("#codAlmacen2").val());
                var idJefatura = $.trim($("#idJefatura2").val());
                var idEECC = $.trim($("#idEECC2").val());

                jsonValida = { codCentro: codCentro, codAlmacen: codAlmacen, idJefatura: idJefatura, idEECC: idEECC };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: 'updateAlmacen',
                    data: {
                        codCentro : codCentro,
                        codAlmacen : codAlmacen,
                        idJefatura : idJefatura,
                        idEECC : idEECC
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbAlmacen);
                        initDataTable('#data-table');
                        modal('modalEditAlmacen');
                        mostrarNotificacion('success', 'Success', 'Se actualizo correctamente');
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }



        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>