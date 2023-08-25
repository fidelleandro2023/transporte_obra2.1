<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <style>
            .cuadrado {
                background-color: #afafaf;
                width: 20px;
                height: 3px;
                margin-top:44px;
            }

            .c_culminado {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: #28a745;
                text-align: center;
            }

            .c_actual {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: #007bff;
                text-align: center;       	
            }

            .c_siom {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: #F7F7F7;
                text-align: center;
                content:url("<?php echo base_url(); ?>public/img/iconos/logo-navbar.png");            	     	
            }

            .c_pendiente {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: #bfbfbf;
                text-align: center;
            }

            .c_suspendido {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: #e02a82;
                text-align: center;
            }

            .c_cancelado {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: rgba(248,80,50,1);
                text-align: center;
            }

            .c_trunco {
                border: 2px solid #afafaf;
                width: 90px;
                height: 90px;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                border-radius: 50%;
                background: rgba(241,231,103,1);
                text-align: center;
            }

            .carga-modulo {
                width: 1080px; 
                height: 1000px; 
                border: 3px solid #555;
                text-align: center;
                margin-left:100px;
                position:relative;            	
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
                    <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2 class="text-center">DETALLE ITEMFAULT: <strong id="itemTitle"><?php echo $item ?></strong></h2>

                    <div class="text-center">
                        <div id="barraProgreso">
                            <?php echo $htmlBarraProgreso ?>
                        </div>
                    </div>

                    <div class="text-center">
                        
                            <div class="card container">
                                <div class="card-block" id="divModuloLoad">
                                </div>
                            </div>
                        
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modalEditEntidadesEjec"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" style="margin: auto;font-weight: bold;">(FORMULARIO DISE&Ntilde;O)</h3>    
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <div class="form-group">
                                
                                    <form id="formDiseno" method="POST" class="form-horizontal">  
                                        <div id="tab_cost">
                                            <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                    INGRESAR ARCHIVO
                                                </div>
                                            </div>   
                                                
                                            <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                    Expediente dise&ntilde;o: Archivo .rar (Archivo de metrados, planos, fotos y documentos)
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-sm-12 col-md-12 form-inline">
                                                        <div class="col-12">
                                                            <input id="fileExpedienteDiseno" name="fileExpedienteDiseno" type="file" accept=".zip,.rar" onchange="habilitarAceptar2()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>COMENTARIO</label>
                                                        <textarea id="textareaComentario" name="textareaComentario" class="form-control"></textarea>
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                                <button type="submit" id="btnAceptarEnt"  class="btn btn-primary" disabled>Aceptar</button>
                                            </div>
                                        </div> 
                                    </form>           
                                </div>
                            </div>                                        
                        </div>
                    </div>
                </div>
            </div>
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
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

        <!--  -->
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/js/js_itemfault/js_gestion_itemfault.js?v=<?php echo time();?>"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>             

        <script type="text/javascript">

            function cargarPreDiseno(itemplan, has_coax, has_fo) {
                $("#divModuloLoad").load("obtFormularioPreDiseno?itemplan=" + itemplan + "&has_coax=" + has_coax + "&has_fo=" + has_fo);
            }
/*
            function cargarEnLicencia(itemplan) {
                $("#divModuloLoad").load("obtFormularioEnLicencia?itemplan=" + itemplan);
            }

            function cargarEnAprobacion(itemplan) {
                $("#divModuloLoad").load("obtFormularioEnAprobacion?itemplan=" + itemplan);
            }
*/
            function cargarEnObra(itemplan) {
                $("#divModuloLoad").load("obtFormularioEnObra?itemplan=" + itemplan);
            }

            function cargarPreLiquidado(itemplan) {
                $("#divModuloLoad").load("obtFormularioPreLiquidado?itemplan=" + itemplan);
            }

            function cargarEnValidacion(itemplan) {
                $("#divModuloLoad").load("obtFormularioEnValidacion?itemplan=" + itemplan);
            }

            function cargarTerminado(itemplan) {
                $("#divModuloLoad").load("obtFormularioTerminado?itemplan=" + itemplan);
            }

            function cargarEnCertificacion(itemplan) {
                $("#divModuloLoad").load("obtFormularioEnCertificacion?itemplan=" + itemplan);
            }

            function cargarCertificado(itemplan) {
                $("#divModuloLoad").load("obtFormularioCertificado?itemplan=" + itemplan);
            }

        </script>


    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>