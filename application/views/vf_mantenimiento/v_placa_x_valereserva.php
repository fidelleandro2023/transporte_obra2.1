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
                           <h2>MANTENIMIENTO DE PLACA CON VALES DE RESERVA</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
                                <div class="card-block"> 
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>REGISTRAR VR CON UNA PLACA</label><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="openModalRegPlacaxVR()">Nuevo</button>
                                            </div>
                                        </div>
                                
                                    </div>
		   				            <div id="contTabla" class="table-responsive">
								        <?php echo $tablaPlacaxVR?>
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

                            <div class="modal fade" role="dialog" id="modalRegPlacaxVR" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE PLACAS</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegPlacaxVR" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="placa">PLACA: </label>
                                                        <select id="placa" name="placa" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="codigoVR">VALE DE RESERVA: </label>
                                                        <input id="codigoVR" placeholder="Ingrese VR.." type="text" class="form-control">
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btnSavePlacaxVR" class="btn btn-success" onclick="savePlacaxVR()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalEditPlacaxVR" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModalEdit" style="margin: auto;font-weight: bold;" class="modal-title">EDITAR PLACA CON VALE DE RESERVA</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formEditMat" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="placa2">PLACA: </label>
                                                        <select id="placa2" name="placa2" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="codigoVR2">VALE DE RESERVA: </label>
                                                        <input id="codigoVR2" placeholder="Ingrese VR.." type="text" class="form-control" readonly>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="btnSaveEdit" class="btn btn-success" onclick="updatePlacaxVR()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

            var placaGlob = null;
            var codigoVRGlob = null;

            function openModalRegPlacaxVR(){
                $("#codigoVR").val(null);
                $.ajax({
                    type: 'POST',
                    url: 'getComboPlaca',
                    data: { placa : placaGlob}
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $("#placa").html(data.cmbPlaca);
                        modal('modalRegPlacaxVR');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer la placa');
                    }
                });
                
            }


            function openEditPlacaxVR(component){
                placaGlob = $(component).data('placa');
                codigoVRGlob = $(component).data('codigovr');
                $.ajax({
                    type: 'POST',
                    url: 'getDetPlacaxVREdit',
                    data: { placa : placaGlob,
                            codigoVR : codigoVRGlob }
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $("#placa2").html(data.cmbPlaca);
                        $("#codigoVR2").val(data.codigoVR);
                        modal('modalEditPlacaxVR');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer la placa con su VR');
                    }
                });

            }


            function savePlacaxVR(){

                var placa = $.trim($("#placa").val());
                var codigoVR = $.trim($("#codigoVR").val());

                jsonValida = { placa: placa, codigoVR: codigoVR };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regPlacaxVR',
                    data: {
                        placa : placa,
                        codigoVR : codigoVR
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbPlacaxVR);
                        initDataTable('#data-table');
                        modal('modalRegPlacaxVR');
                        mostrarNotificacion('success', 'Success', data.msj);
                        
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function updatePlacaxVR(){

                var placa = $.trim($("#placa2").val());

                if (placa == null) {
                    mostrarNotificacion('error', 'Error','Debe seleccionar la placa para poder registrar!!');
                    return;
                }

                if(codigoVRGlob != null){
                    $.ajax({
                        type: 'POST',
                        url: 'updatePlacaxVR',
                        data: {
                            placa : placa,
                            codigoVR : codigoVRGlob
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbPlacaxVR);
                            initDataTable('#data-table');
                            modal('modalEditPlacaxVR');
                            mostrarNotificacion('success', 'Success', data.msj);
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    });
                }

            }


          

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>