<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

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
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.css" />
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">
</head>

<body data-ma-theme="entel">
    <main class="main">
        <div class="page-loader">
            <svg x="0" y="0" width="258" height="258">
                <g clip-path="url(#clip-path)">
                    <path class="tree" id="g" />
                </g>

                <clipPath id="clip-path">
                    <path id="path" class="circle-mask" />
                </clipPath>
            </svg>
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
                <a href="" title=""><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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

        <aside class="chat">
            <div class="chat__header">
                <h2 class="chat__title">Chat <small>Currently 20 contacts online</small></h2>

                <div class="chat__search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <i class="form-group__bar"></i>
                    </div>
                </div>
            </div>

            <div class="listview listview--hover chat__buddies scrollbar-inner">
                <a class="listview__item chat__available">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/7.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>hey, how are you doing.</p>
                    </div>
                </a>

                <a class="listview__item chat__available">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/5.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>hmm...</p>
                    </div>
                </a>

                <a class="listview__item chat__away">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/3.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>all good</p>
                    </div>
                </a>

                <a class="listview__item">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>morbi leo risus portaac consectetur vestibulum at eros.</p>
                    </div>
                </a>

                <a class="listview__item">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/6.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>fusce dapibus</p>
                    </div>
                </a>

                <a class="listview__item chat__busy">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/9.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>cras mattis consectetur purus sit amet fermentum.</p>
                    </div>
                </a>
            </div>

            <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
        </aside>

        <section class="content content--full">

            <div class="content__inner">
                <h2>EXTRACTOR</h2>
                <div class="card">
                    <?php echo $listartabla ?>
                </div>
            </div>

            <footer class="footer hidden-xs-down">
                <p>Telefonica del Peru</p>
            </footer>

        </section>
    </main>


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
    <script src="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

    <!--  -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
    <script type="text/javascript">
        function descargarPlanobra() {
            $.ajax({
                type: 'POST',
                url: 'reportePlanobra',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "PLANOBRA.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }

        function descargarReporteOC() {
            $.ajax({
                type: 'POST',
                url: 'reporteOC',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "ORDEN_COMPRA.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }

        function descargarReportePartidas() {
            $.ajax({
                type: 'POST',
                url: 'reportePartidas',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "PARTIDAS.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }

        function descargarImpactoAmbiental() {
            $.ajax({
                type: 'POST',
                url: 'generarImpactoAmbiental',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "IMPACTO_AMBIENTAL.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }
		
		function generarFirmaDigital() {
            $.ajax({
                type: 'POST',
                url: 'generarFirmaDigital',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "FIRMA_DIGITAL.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }
		
		function generarPartidaSBE() {
            $.ajax({
                type: 'POST',
                url: 'generarPartidaSBE',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "PARTIDAS_SBE.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }
		
		function generarExcelSolOcFijaMasDep() {
            $.ajax({
                type: 'POST',
                url: 'generarExcelSolOcFijaMasDep',
                data: {},
                dataType: "html", //html
                contentType: "application/x-www-form-urlencoded",
                beforeSend: () => {
                    $('body').loading({
                        message: 'Espere por favor...'
                    });
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.archivo != null && data.archivo != undefined) {
                        var $a = $("<a>");
                        $a.attr("href", data.archivo);
                        $("body").append($a);
                        $a.attr("download", "SOL_OC_fija_desp.xls");
                        $a[0].click();
                        $a.remove();
                    }
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }

            }).always(() => {
                $('body').loading('destroy')
            });
        }
    </script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css" />
</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

</html>