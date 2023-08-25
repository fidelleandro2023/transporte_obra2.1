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
                           <h2>MANTENIMIENTO DE PARTIDAS</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
                                <div class="card-block"> 
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>REGISTRAR PARTIDA</label><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="openModalRegPartida()">Nueva Partida</button>
                                            </div>
                                        </div>
                                
                                    </div>
		   				            <div id="contTabla" class="table-responsive">
								        <?php echo $tablaPartidas?>
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

                            <div class="modal fade" role="dialog" id="modalRegPartida" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE PARTIDA</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegPart" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descripCod">CODIGO: </label>
                                                        <input id="descripCod" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descripPart">DESCRIPCION: </label>
                                                        <input id="descripPart" placeholder="Ingrese descripcion.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="baremo">BAREMO: </label>
                                                        <input id="baremo" placeholder="Ingrese baremo.." type="number" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descKitMat">KIT MATERIAL: </label>
                                                        <input id="descKitMat" placeholder="Ingrese descripcion.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="costoMat">COSTO MATERIAL: </label>
                                                        <input id="costoMat" placeholder="Ingrese costo.." type="number" class="form-control">
                                                    </div>

                                                    <div class="col-sm-6 form-group">
                                                        <label for="precioDiseno">TIPO DE PRECIO: </label>
                                                        <select id="precioDiseno" name="precioDiseno" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group" id="contTipoMat">
                                                        <label for="idTipoPlanta">TIPOS: </label>
                                                        <select id="idTipoPlanta" name="idTipoPlanta" class="select2 form-control">
                                                            <option value="">Seleccionar Tipo</option>
                                                            <option value="1">PLANTA INTERNA</option>
                                                            <option value="2">PLANTA EXTERNA</option>
                                                        </select>
                                                    </div>

                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveMat" class="btn btn-success" onclick="savePartida()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalEditPartida" data-backdrop="static" data-keyboard="false" tabindex="-1">
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
                                                        <label for="descripCodEdit">CODIGO: </label>
                                                        <input id="descripCodEdit" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descripPartEdit">DESCRIPCION: </label>
                                                        <input id="descripPartEdit" placeholder="Ingrese descripcion.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="baremoEdit">BAREMO: </label>
                                                        <input id="baremoEdit" placeholder="Ingrese baremo.." type="number" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descKitMatEdit">KIT MATERIAL: </label>
                                                        <input id="descKitMatEdit" placeholder="Ingrese descripcion.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="costoMatEdit">COSTO MATERIAL: </label>
                                                        <input id="costoMatEdit" placeholder="Ingrese costo.." type="number" class="form-control">
                                                    </div>

                                                    <div class="col-sm-6 form-group">
                                                        <label for="precioDisenoEdit">TIPO DE PRECIO: </label>
                                                        <select id="precioDisenoEdit" name="precioDisenoEdit" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group" id="contTipoMatEdit">
                                                        <label for="idTipoPlantaEdit">TIPOS: </label>
                                                        <select id="idTipoPlantaEdit" name="idTipoPlantaEdit" class="select2 form-control">
                                                            <option value="">Seleccionar Tipo</option>
                                                            <option value="1">PLANTA INTERNA</option>
                                                            <option value="2">PLANTA EXTERNA</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectEstado">ESTADO: </label>
                                                        <select id="selectEstado" name="selectEstado" class="select2 form-control">
                                                            <option value="">Seleccionar Tipo</option>
                                                            <option value="1">ACTIVO</option>
                                                            <option value="2">INACTIVO</option>
                                                        </select>
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contEdit">
                                            <button type="button" id="btnSaveEdit" class="btn btn-success" onclick="updatePartida()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>







                           <div class="modal fade" role="dialog" id="modalRegProyEstPart" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO PARTIDA PROYECTO ESTACION</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegMat" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="selectPart1">PARTIDA: </label>
                                                        <select id="selectPart1" name="selectPart1" class="select2 form-control" onchange="limpiarCmbEstacion()">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectProy1">PROYECTO: </label>
                                                        <select id="selectProy1" name="selectProy1" class="select2 form-control" onchange="getEstacionesByPartProy()">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 form-group">
                                                        <label for="selectEst1">ESTACION: </label>
                                                        <select id="selectEst1" name="selectEst1" class="select2 form-control" multiple>
                                                        </select>
                                                    </div>


                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveMat" class="btn btn-success" onclick="saveProyEstPart()">Guardar</button>
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

            var idActividadGlob = null;

            function openModalRegPartida(){
                $("#descripCod").val(null);
                $("#descripPart").val(null);
                $("#baremo").val(null);
                $("#descKitMat").val(null);
                $('#costoMat').val(null);
                $("#idTipoPlanta").val(null);
                $('#idTipoPlanta').change();

                $.ajax({
                    type: 'POST',
                    url: 'getCmbPrecDiseno'
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#precioDiseno').html(data.cmbPrecDiseno);
                        modal('modalRegPartida');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
                    }
                });
            }


            function openEditPartida(component){
                idActividadGlob = $(component).data('idactividad');
                $.ajax({
                    type: 'POST',
                    url: 'getDetPartidaEdit',
                    data: { idActividad : idActividadGlob}
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#descripCodEdit').val(data.codigo);
                        $('#descripPartEdit').val(data.descripcion);
                        $('#baremoEdit').val(data.baremo);
                        $('#descKitMatEdit').val(data.kit_material);
                        $('#costoMatEdit').val(data.costo_material);
                        $('#precioDisenoEdit').html(data.cmbPrecioDiseno);
                        $('#idTipoPlantaEdit').val(data.flg_tipo);
                        $('#idTipoPlantaEdit').change();
                        $('#selectEstado').val(data.estado);
                        $('#selectEstado').change();
                        modal('modalEditPartida');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer la partida');
                    }
                });

            }

            var idPartidaNewGlob = null;

            function savePartida(){

                var codigo = $.trim($("#descripCod").val());
                var descPartida = $.trim($("#descripPart").val());
                var baremo = $.trim($("#baremo").val());
                var descKitMat = $.trim($("#descKitMat").val());
                var costoMat = $.trim($("#costoMat").val());
                var precioDiseno = $.trim($("#precioDiseno").val());
                var tipoPLanta = $.trim($("#idTipoPlanta").val());

                jsonValida = { codigo: codigo, descPartida: descPartida, baremo: baremo, /*descKitMat: descKitMat, costoMat: costoMat,*/ precioDiseno: precioDiseno, tipoPLanta : tipoPLanta };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regPartida',
                    data: {
                        codigo : codigo,
                        descPartida : descPartida,
                        baremo : baremo,
                        descKitMat : descKitMat,
                        costoMat : costoMat,
                        precioDiseno : precioDiseno,
                        tipoPLanta : tipoPLanta
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbPartidas);
                        initDataTable('#data-table');
                        modal('modalRegPartida');
                        mostrarNotificacion('success', 'Success', data.msj);
                        swal({
                            title: 'Desea asociar la nueva partida a un proyecto, estacion(es)??',
                            text: 'Asegurese de validar la informacion!',
                            type: 'warning',
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: 'OK!'

                        }).then(function () {
                            idPartidaNewGlob = data.idActividad;
                            $.ajax({
                                type: 'POST',
                                url: 'getCmbProyEstPart',
                                data: {
                                    idPartida : idPartidaNewGlob
                                }
                            }).done(function (data) {
                                data = JSON.parse(data);
                                if (data.error == 0) {
                                    $('#selectProy1').html(data.cmbProyecto);
                                    $('#selectEst1').html(data.cmbEstacion);
                                    $('#selectPart1').html(data.cmbPartida);
                                    modal('modalRegProyEstPart');
                                } else {
                                    mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
                                }
                            });
                        });
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function updatePartida(){
                var codigo = $.trim($('#descripCodEdit').val());
                var descPartida = $.trim($('#descripPartEdit').val());
                var baremo = $.trim($('#baremoEdit').val());
                var descKitMat = $.trim($('#descKitMatEdit').val());
                var costoMat = $.trim($('#costoMatEdit').val());
                var precioDiseno = $.trim($('#precioDisenoEdit').val());
                var tipoPLanta = $.trim($('#idTipoPlantaEdit').val());
                var estado = $.trim($('#selectEstado').val());

                jsonValida = { codigo: codigo, descPartida: descPartida, baremo: baremo, descKitMat: descKitMat, costoMat: costoMat, precioDiseno: precioDiseno, tipoPLanta : tipoPLanta, estado : estado };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                if(idActividadGlob != null){
                    $.ajax({
                        type: 'POST',
                        url: 'updatePartida',
                        data: {
                            idActividad : idActividadGlob,
                            codigo : codigo,
                            descPartida : descPartida,
                            baremo : baremo,
                            descKitMat : descKitMat,
                            costoMat : costoMat,
                            precioDiseno : precioDiseno,
                            tipoPLanta : tipoPLanta,
                            estado : (estado == 1 ? '1' : '2')
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbPartidas);
                            initDataTable('#data-table');
                            modal('modalEditPartida');
                            mostrarNotificacion('success', 'Success', data.msj);
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    });
                }

            }


            function saveProyEstPart(){

                var idProyecto = $.trim($("#selectProy1").val());
                var idEstacion = $.trim($("#selectEst1").val());
                var idPartida = $.trim($("#selectPart1").val());

                jsonValida = { idProyecto: idProyecto, idEstacion: idEstacion, idPartida: idPartida };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regProyEstPartByPart',
                    data: {
                        idProyecto : idProyecto,
                        idEstacion : idEstacion,
                        idPartida : idPartida
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        modal('modalRegProyEstPart');
                        mostrarNotificacion('success', 'Success', data.msj);
                        console.log(data.arrayIdAct);
                        window.location.href = "mantProyEstPart?arryAct=" + data.arrayIdAct;
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }
            
            function getEstacionesByPartProy(){
                var idProyecto = $.trim($("#selectProy1").val());
                var idPartida = $.trim($("#selectPart1").val());
                if(idProyecto != null && idPartida != null){
                    $.ajax({
                        type: 'POST',
                        url: 'getEstByPartProy',
                        data: { idProyecto : idProyecto,
                                idPartida  : idPartida
                        }

                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#selectEst1').html(data.cmbEstacion);
                        } else {
                            mostrarNotificacion('error', 'Error', 'Hubo un error al traer las estaciones');
                        }
                    });
                }
            }

            function limpiarCmbEstacion(){
                var idProyecto = $.trim($("#selectProy1").val());
                var idPartida = $.trim($("#selectPart1").val());

                jsonValida = { idProyecto: idProyecto, idPartida: idPartida };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    $('#selectEst1').html(null);
                }
            }


        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>