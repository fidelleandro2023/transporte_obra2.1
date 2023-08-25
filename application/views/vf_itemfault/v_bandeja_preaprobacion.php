<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">


        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css?v=<?php echo time(); ?>">
        <style>
            .size{
                width: 111px;
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
                    <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
                </div>

                <?php include('application/views/v_opciones.php'); ?>
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
                    <h2>BANDEJA DE APROBACION DE ITEMFAULT</h2>
                    <hr>
                    <div class="card">		   				                    

                        <div class="card-block"> 
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>RED DE SERVICIO</label>
                                        <select id="selectServicio" name="selectServicio" class="select2 form-control" onchange="changeServicio()">
                                            <option value="">&nbsp;</option>
                                            <?php
                                            foreach ($servicio as $row) {
                                                ?> 
                                                <option value="<?php echo $row->idServicio ?>"><?php echo $row->servicioDesc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>ELEMENTO DE RED DE SERVICIO</label>
                                        <select id="selectElementoServicio" name="selectSubproy" class="select2 form-control">
                                            <option value="">&nbsp;</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>FECHA DE CREACI&Oacute;N</label>
                                        <input id="inputcreacion" name="inputFechaAveria" type="text" class="form-control date-picker" placeholder="Pick a date">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMFAULT</label>
                                        <input id="inputItemfaut" name="inputNombrePlan" type="text" class="form-control">
                                    </div> 
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>NOMBRE DE URA</label>
                                        <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control">
                                    </div> 
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>RED DE SERVICIO</label>
                                        <select id="selectEstado" name="selectServicio" class="select2 form-control" onchange="changeServicio()">
                                            <option value="">&nbsp;</option>
                                            <?php
                                            foreach ($estado as $row) {
                                                ?> 
                                                <option value="<?php echo $row->idEstadoItemfault ?>"><?php echo $row->estadoItemfaultDesc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>AREA/GERENCIA</label>
                                        <select id="selectGerencia" name="selectSubproy" class="select2 form-control">
                                            <option value="">&nbsp;</option>
                                            <?php
                                            foreach ($gerencia as $row) {
                                                ?> 
                                                <option value="<?php echo $row->idGerencia ?>"><?php echo $row->gerenciaDesc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>EVENTO</label>
                                        <select id="selectEvento" name="selectEvento" class="select2 form-control" onchange="changeEvento()">
                                            <option value="">&nbsp;</option>
                                            <?php
                                            foreach ($evento as $row) {
                                                ?> 
                                                <option value="<?php echo $row->idEvento ?>"><?php echo $row->EventoDesc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>SUB EVENTO</label>
                                        <select id="selectSubEvento" name="selectSubEvento" class="select2 form-control" onchange="changeAveria()">
                                            <option value="">&nbsp;</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <button class="btn btn-success waves-effect" onclick="consultaItemfault()">CONSULTAR</button>
                                    </div> 
                                </div>
                            </div>
                            <div id="contTabla" class="table-responsive">
                                <?php echo isset($tablaConsultaItemfault) ? $tablaConsultaItemfault : null ?>

                            </div>
                        </div>
                    </div>
            </section>

        </main>
        <!-- Small -->

        <div class="modal fade" id="modalExpediente" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-center">INGRESE VALE DE RESERVA</h1>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Ingrese Vale de Reserva</label>
                                    <input maxlength="7" id="valeReserva" class="form-control" type="text" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="boton_vale_reserva"  class="btn btn-info">CONFIRMAR</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Older IE warning message -->

        <!-- POPUP LOG-->

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
        <script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
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
        <!--CODIGO PARA EL FILE IMPUT--> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-input/fileinput.min.css">
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-input/fileinput.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/numeric/jquery.numeric.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
        <script src="<?php echo base_url(); ?>public/js/js_itemfault/preaprobacion.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="https://www.w3schools.com/lib/w3.js"></script>
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>