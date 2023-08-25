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
                               <h2>VALIDACI&Oacute;N  DE RETIRO</h2>
                               <hr>

                               <div class="card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label>ITEMPLAN</label>
                                                    <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label># RETIRO</label>
                                                    <input id="nroRetiro" type="text" class="form-control input-mask" placeholder="# de retiro" autocomplete="off" maxlength="9" style="border-bottom: 1px solid lightgrey">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label>ESTADO </label>
                                                    <select id="estadoSoli" name="responsable" class="select2 form-control">
                                                        <option value="">Seleccionar estado</option>
                                                        <option value="2">SOLICITUD LIQUIDADA</option>
                                                        <option value="4">SOLICITUD VALIDADA</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label>FECHA DE LIQUIDACI&Oacute;N</label>
                                                    <input id="fechaLiqui" type="date" class="form-control input-mask" autocomplete="off" style="border-bottom: 1px solid lightgrey">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <br><br>
                                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="contTabla" class="table-responsive">
                                            <?php echo $tablaBolsaPresupuesto ?>
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

                            <div class="modal fade" role="dialog" id="idModalVerValidacion" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">DETALLE DE VALIDACI&Oacute;N</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idMontoVali">Monto Aprobado(S/) :</label>
                                                        <h6 id="idMontoAprob"></h6>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idFechaVali">Fecha Validada:</label>
                                                        <h6 id="idFechaVali"></h6>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idMontoVali">Monto Validado(S/) :</label>
                                                        <h6 id="idMontoVali"></h6>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idMontoDevuelto">Monto Devuelto(S/) :</label>
                                                        <h6 id="idMontoDevuelto" style="margin: auto;font-weight: bold; color: red"></h6>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardarLiqui">
                                            <button type="button" id="btnSaveLiqui" class="btn btn-success">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="modal fade" id="abrirModalAlertValida" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header" style="background:red">
                                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta Operaci&oacute;n?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <a>Al aceptar, se validar&aacute; la liquidaci&oacute;n del retiro.</a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success" onclick="updateRetiroBolsaPresu()">Aceptar</button>
                                    </div>
                                    </div>
                                </div>
                            </div> -->


                            <div class="modal fade" role="dialog" id="abrirModalAlertValida" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">VALIDAR RETIRO</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="comment"># Retiro :</label>
                                                        <h6 id="idNroRetiro"></h6>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idMontoAprobVali">Monto Aprobado(S/) :</label>
                                                        <h6 id="idMontoAprobVali"></h6>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idMontoLiqui">Monto Liquidado(S/) :</label>
                                                        <h6 id="idMontoLiqui"></h6>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="comment">Monto a Validar(S/) :</label>
                                                        <input type="number" class="form-control" id="idMontoValida" onkeyup="validarMonto()">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idMontoDevu">Monto a devuelto(S/) :</label>
                                                        <h6 id="idMontoDevu" style="margin: auto;font-weight: bold; color: red"></h6>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveBolsa" class="btn btn-success" onclick="updateRetiroBolsaPresu()">Guardar</button>
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

            var idBolsaGlob = null;
            var itemplanGlob = null;
            var nroRetiroGlob = null;
            var montoLiquiGlob = null;
            var montoLiquiCal = null;
            var montoValidaGlob = null;
            var montoAprobGlob = null;

            function verEviLiqui(component){
                var rutaPDF = $(component).data('rutapdf');
                if(rutaPDF != null){
                    window.open(rutaPDF);
                }
            }
            
            function validarMonto(){
                var montoValida = $("#idMontoValida").val();
                var newMontoDevu = montoAprobGlob - montoValida;
                $("#idMontoDevu").text(newMontoDevu);
                if(montoValida > montoAprobGlob){
                    $("#btnSaveBolsa").attr("disabled", "true");
                }else{
                    $("#btnSaveBolsa").removeAttr("disabled");
                }
            }

            function abrirModalAlertValida(component){

                idBolsaGlob = $(component).data('idbolsa');
                itemplanGlob = $(component).data('itemplan');
                nroRetiroGlob = $(component).data('nro_retiro');
                montoLiquiGlob = $(component).data('montoliqui');
                montoAprobGlob = $(component).data('montoaprob');
                var montoAprobVista = $(component).data('montoaprobvista');
                montoLiquiCal = $(component).data('montoliquical');

                $("#idMontoLiqui").text(montoLiquiGlob);
                $("#idNroRetiro").text(nroRetiroGlob);
                $("#idMontoValida").val(montoAprobGlob);
                $("#idMontoAprobVali").text(montoAprobVista);
                $("#idMontoDevu").text(montoAprobGlob-montoAprobGlob);

                modal('abrirModalAlertValida');
            }

            function abrirModalValida(component){
                var montoValida = $(component).data('montovalida');
                var fechaValida = $(component).data('fechavalida');
                var montoDevuelto = $(component).data('montodevuelto');
                var montoAprob = $(component).data('montoaprob');

                $('#idMontoVali').text(montoValida);
                $('#idFechaVali').text(fechaValida);
                $('#idMontoDevuelto').text(montoDevuelto);
                $('#idMontoAprob').text(montoAprob);

                modal('idModalVerValidacion');
            }

            function updateRetiroBolsaPresu(){
                var montoValida = $("#idMontoValida").val();

                if(montoValida > montoAprobGlob){
                    mostrarNotificacion('error', 'Error', "EL monto a validar no puede exceder al aprobado!!");
                    return;
                }
                
                if(montoValida == null || montoValida == '' || montoValida == undefined){
                    mostrarNotificacion('error', 'Error', "Debe ingresar un monto a validar!!");
                    return;
                }

                if(idBolsaGlob != null && itemplanGlob != null && nroRetiroGlob != null) {
                    $.ajax({
                        type: 'POST',
                        url: 'updateRetBolsa',
                        data: {
                            idBolsa : idBolsaGlob,
                            itemplan: itemplanGlob,
                            nroRetiro: nroRetiroGlob,
                            montoAprob: montoAprobGlob,
                            montoValida: montoValida
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbLiquiRetiro);
                            initDataTable('#data-table');
                            modal('abrirModalAlertValida');
                            mostrarNotificacion('success', 'Success', data.msj);
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    });
                }else{
                    mostrarNotificacion('error', 'Error', 'No selecciono correctamente la solicitud!!');
                }

            }

            function filtrarTabla(){

                var erroItemPlan = '';
                var erroNroRetiro = '';
                var itemplan = $.trim($('#txtItemPlan').val());
                var nroRetiro = $.trim($('#nroRetiro').val());
                var estadoSoli = $('#estadoSoli').val();
                var fechaLiqui = $('#fechaLiqui').val();


                if(itemplan.length > 13 || (itemplan.length >= 1 && itemplan.length < 12)){
                    erroItemPlan = 'ItemPlan Invalido.'
                }

                if((nroRetiro.length < 9 || nroRetiro.length > 9) && (nroRetiro != null && nroRetiro != '') ){
                    erroNroRetiro = '# Retiro Invalido.'
                }

                if(erroItemPlan == '' && erroNroRetiro == ''){

                    $.ajax({
                        type	:	'POST',
                        'url'	:	'getLiquiRetByFilt',
                        data	:	{ itemplan : itemplan,
                                      nroRetiro : nroRetiro,
                                      estadoSoli : estadoSoli,
                                      fechaLiqui  : fechaLiqui
                                    },
                        'async'	:	false
                    })
                    .done(function(data){
                        var data	=	JSON.parse(data);
                        if(data.error == 0){
                            $('#contTabla').html(data.tablaLiquiRet);
                            initDataTable('#data-table');

                        }else if(data.error == 1){

                            mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                        }
                    });

                }else{
                    if(erroItemPlan != '' && erroNroRetiro == ''){
                        mostrarNotificacion('error','ItemPlan',erroItemPlan);
                    }else if(erroNroRetiro != '' && erroItemPlan == ''){
                        mostrarNotificacion('error','# Retiro',erroNroRetiro);
                    }else{
                        mostrarNotificacion('error','Error','Itemplan y # Retiro invalidos!!');
                    }

                }

            }

           


        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>