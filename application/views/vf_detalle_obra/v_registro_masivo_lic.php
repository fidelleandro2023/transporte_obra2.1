<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
   
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="UTF-8">
        <!-- Vendor styles -->
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        <style>
            .select2-dropdown {
                z-index: 100000;
            }
            .fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}
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
                <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
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
                    <h2><?php echo $title ?></h2>
                    <div class="">    
                        <div class="card" style="">
                            <div class="card-block">
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Exportar</label>
                                            <input type="button" id="btnExportFormato" value="Exportar" class="btn-success" onclick="generarExcelPO();">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Importar</label>
                                            <button class="btn-success" id="btnAceptarFormulario">Aceptar</button>
                                            <div id="dropzoneLic" class="dropzone" >
                                                </div>
                                                <hr style="border:1;">
                                                <div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                         <div class="row col-md-1">
                                            
                                        </div>
                                        
                                        <div class="row col-md-4">
                                            <label>Menor al rango del kit</label>&nbsp;&nbsp;
                                            <input type="text" class="form-control" style="background:#00b300;height:20px;width:30px" disabled>                            
                                        </div>
                                        <div class="row col-md-4">
                                            <label>Mayor al rango del kit</label>&nbsp;&nbsp;
                                            <input type="text" class="form-control" style="background:#ff6600;height:20px;width:30px" disabled>                            
                                        </div>
                                        <div class="row col-md-3">
                                            <button class="btn-success" id="btnRegisPO" onclick="guardarPO()">Registrar PO</button>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>VR</label>
                                            <input id="contVr" type="text" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>EECC</label>
                                            <input id="contEmpresaColab" type="text" class="form-control" disabled>
                                        </div>
                                    </div> -->
                                    <!-- <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Jefatura</label>
                                            <input id="contJefatura" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Centro</label>
                                            <input id="contCentro" type="text" class="form-control" disabled>
                                        </div>
                                    </div>

                                    <div class="row">                            
                                        <div class="form-group col-md-6">
                                            <label>Almacen</label>
                                            <input id="contAlmacen" type="text" class="form-control" disabled>
                                        </div>

                                        <div class="form-group col-md-6">      
                                        <button class="btn-info" onclick="openModalBloc()">bloc Notas</button>                          
                                            <div id="dropzone1" class="dropzone" >
                                            </div>
                                            <hr style="border:1;">
                                            <div>
                                                <a href="uploads/vale_reserva_solicitud/formato_VR.xlsx" download="formato_VR.xlsx"><i class="zmdi zmdi-hc-2x zmdi-download"></i> DESCARGAR FORMATO EXCEL</a>
                                            </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="row"> -->
                                        <!-- <div class="form-group">
                                            <a>Contenido del documento: Tipo de solicitud/Material/textro Breve/cantidad In./cant Fin./vr</a>                
                                        <div> -->
                                    <!-- </div> -->
                                    <div id="contTablaError" class="table-responsive"></div>
                                   
                                    <!-- <div class="footer">
                                      <button class="btn-success" id="btnRegisPO" onclick="guardarPO()">Registrar PO</button>
                                    </div> -->
                                </div>

                            </div>
                        </div>
                    </div>    
                </div>
            </section>

            <div style="visibility:hidden;">
                <form method="POST" id="formGenerarMATPO">
                </form>
            </div>


        </main>
    </body>
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
    <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>        
    <script src="<?php echo base_url();?>public/js/js_po/registro_masivo_lic.js?v=<?php echo time();?>"></script>
    
<script>

    $(document).ready(function(){
        $('.dz-message').html('<span>Subir archivo de materiales </span>')
    });

</script>