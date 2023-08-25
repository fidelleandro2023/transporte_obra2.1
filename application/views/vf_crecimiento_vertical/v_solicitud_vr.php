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
                               <h2>SOLICITUD DE VR CV</h2>
                               <hr>

                               <div class="card">
                                    <div class="card-block">
                                        <?php echo $tabsECC ?>
                                    </div>
                                </div>

                                <!-- <div class="card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-3">
                                                <div class="form-group">
                                                    <label>REGISTRAR SOLICITUD</label><br>
                                                    <button class="btn btn-success waves-effect" type="button" onclick="openModalRegiSolicitud()">Nueva Solicitud</button>
                                                </div>
                                            </div>

                                        </div>
                                        <div id="contTabla" class="table-responsive">
                                            
                                    </div>
                                    </div>
                                </div> -->

		   				    </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p> © Material Admin Responsive. All rights reserved.</p>

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
                            <div class="modal fade" role="dialog" id="modalRegSolicitudRetiro" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">NUEVA SOLICITUD DE RETIRO </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegistrarEntidad" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-8 form-group">
                                                        <label for="idDescCuenta">Cuentas: </label>
                                                        <select id="idDescCuenta" name="responsable" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 tab-container">
                                                        <div id="contTablaBolsaPresu" class="table-responsive" style="display: none">

                                                        </div>
                                                    </div>

                                                    <div class="col-sm-8 form-group" id="contDescItemPlan" style="display: none">
                                                        <label for="itemplan">ITEMPLAN: </label>
                                                        <div class="input-group">
                                                            <input id="itemplan" type="search" class="form-control" placeholder="Ingrese el itemplan" maxlength="13" onkeyup="validaBusqueda(2)">
                                                            <span class="input-group-btn"><button id="idBtnSearch" type="button" class="btn btn-success waves-effect" onclick="searchItemPlan()" disabled>Buscar</button></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 form-group">
                                                        <br>
                                                        <h6>Busque itemplan en obra!!<h6>
                                                    </div>
                                                    <div class="col-sm-12 tab-container">
                                                        <div id="contTablaItemPlan" class="table-responsive" style="display: none">

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-12 form-group" id="contDescMotivo" style="display: none">
                                                        <label for="idDescMotivo">Motivo:</label>
                                                        <textarea class="form-control" rows="3" id="idDescMotivo"></textarea>
                                                    </div>
                                                    <div class="col-sm-8 form-group" id="contMonto" style="display: none">
                                                        <label for="comment">Monto Solicitado(S/) :</label>
                                                        <input type="number" class="form-control" id="idMontoSoli">
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveBolsa" style="display: none" class="btn btn-success" onclick="openModalValidacion()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalAlertValidacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header" style="background:red">
                                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta operaci&oacute;n?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <a>Al aceptar, se registrar&aacute; la solicitud de retiro.</a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success" onclick="saveSoliRetiro()">Aceptar</button>
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

            function openModalValidacion(){
                modal('modalAlertValidacion');
            }

            function openModalRegiSolicitud(){
                idBolsaGlob = null;
                itemplanGlob = null;
                $("#itemplan").val(null);
                $("#idDescMotivo").val(null);
                $("#idMontoSoli").val(null);
                $('#contTablaBolsaPresu').css("display", "none");
                $('#contTablaItemPlan').css("display", "none");
                $('#contDescItemPlan').css("display", "none");
                $('#contDescMotivo').css("display", "none");
                $('#contMonto').css("display", "none");
                $.ajax({
                    type: 'POST',
                    url: 'getAllBolsaPresu'

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#idDescCuenta').html(data.comboBolsaPresu);
                        $('#contDescItemPlan').css("display", "block");
                    } else {
                        mostrarNotificacion('warning', 'Aviso', 'No se encontr&oacute; ninguna cuenta');
                    }
                });

                modal('modalRegSolicitudRetiro');
            }

            function validaBusqueda(flgValida) {
                if(flgValida == 1){
                    var descCuenta = ($("#idDescCuenta").val()).trim();

                    if (descCuenta.length == 0) {
                        $("#idBtnSearchBolsa").attr("disabled", true);
                    } else {
                        $("#idBtnSearchBolsa").attr("disabled", false);
                    }
                }else if (flgValida == 2){
                    var itemplan = $("#itemplan").val();

                    if (itemplan.length == 13) {
                        $("#idBtnSearch").attr("disabled", false);
                    } else {
                        $("#idBtnSearch").attr("disabled", true);
                    }
                }
            }

            function searchCuentaBolsaPre(){
                var descCuenta = ($("#idDescCuenta").val()).trim();
                if (descCuenta != null && descCuenta != '' && descCuenta != undefined) {
                    $.ajax({
                        type: 'POST',
                        url: 'searchBolsaPresupuesto',
                        data: {
                            descCuenta: descCuenta
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            idBolsaGlob = data.idBolsa;
                            $('#contTablaBolsaPresu').css("display", "block");
                            $('#contTablaBolsaPresu').html(data.tablaBolsaPresu);
                            $('#contDescItemPlan').css("display", "block");
                            console.log('idBolsaGlob: ',idBolsaGlob);
                        } else {
                            mostrarNotificacion('warning', 'Aviso', 'No se encontr&oacute; ninguna cuenta');
                        }
                    });
                }
            }

            function searchItemPlan() {
                var itemplan = $("#itemplan").val();
                if (itemplan != null && itemplan != '' && itemplan != undefined) {
                    $.ajax({
                        type: 'POST',
                        url: 'getItemPlanSoli',
                        data: {
                            itemplan: itemplan
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            itemplanGlob = data.itemplan;
                            $('#contTablaItemPlan').css("display", "block");
                            $('#contTablaItemPlan').html(data.tablaDetItemPlan);
                            if(data.flgMsj == 0){
                                mostrarNotificacion('warning', 'Aviso', 'Debe buscar un itemplan en obra!!');
                                $('#btnSaveBolsa').css("display", "none");
                            }else{
                                $('#btnSaveBolsa').css("display", "block");
                            }
                            $('#contDescMotivo').css("display", "block");
                            $('#contMonto').css("display", "block");
                        } else {
                            mostrarNotificacion('error', 'Error', 'No se encontr&oacute; el itemplan');
                        }
                    });
                }
            }


            function saveSoliRetiro(){
                var motivo = ($("#idDescMotivo").val()).trim();
                var montoSolicitado = $("#idMontoSoli").val();
                var idBolsa = $("#idDescCuenta").val();

                jsonValida = { motivo: motivo, montoSolicitado: montoSolicitado };
                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                if(idBolsa != null && idBolsa != undefined && idBolsa != 0 && itemplanGlob != null) {
                    $.ajax({
                        type: 'POST',
                        url: 'regSoliRetiro',
                        data: {
                            idBolsa: idBolsa,
                            itemplan: itemplanGlob,
                            motivo: motivo,
                            monto: montoSolicitado
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbSoliRetiro);
                            initDataTable('#data-table');
                            modal('modalAlertValidacion');
                            modal('modalRegSolicitudRetiro');
                            if(data.msjAviso != null && data.msjAviso != undefined){
                                mostrarNotificacion('warning', 'Aviso', data.msjAviso);
                            }
                            mostrarNotificacion('success', 'Success', data.msj+' \n Nro_retiro generado: '+data.nroRetiro);
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    });
                }else{
                    mostrarNotificacion('error', 'Error', 'Debe seleccionar una cuenta y asignar un itemplan!!');
                }

            }

            function filtrarTabla(){
                var erroItemPlan = '';
                var erroNroRetiro = '';
                var itemplan = $.trim($('#txtItemPlan').val());
                var nroRetiro = $.trim($('#nroRetiro').val());
                var estadoSoli = $('#estadoSoli').val();
                var fechaRegi = $('#fechaReg').val();


                if(itemplan.length > 13 || (itemplan.length >= 1 && itemplan.length < 12)){
                    console.log('entro al if, itemplan: ', itemplan.length);
                    erroItemPlan = 'ItemPlan Invalido.'
                }

                if((nroRetiro.length < 9 || nroRetiro.length > 9) && (nroRetiro != null && nroRetiro != '') ){
                    console.log('entro al if, nroRet: ', nroRetiro.length);
                    erroNroRetiro = '# Retiro Invalido.'
                }

                if(erroItemPlan == '' && erroNroRetiro == ''){

                    $.ajax({
                        type	:	'POST',
                        'url'	:	'getSoliRetByFiLt',
                        data	:	{ itemplan : itemplan,
                                    nroRetiro : nroRetiro,
                                    estadoSoli : estadoSoli,
                                    fechaRegi  : fechaRegi
                                    },
                        'async'	:	false
                    })
                    .done(function(data){
                        var data	=	JSON.parse(data);
                        if(data.error == 0){
                            $('#contTabla').html(data.tablaSoliRet);
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