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
    </head>

    <body data-ma-theme="entel">
    <main class="main">
        <div class="page-loader">
            <div class="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
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
                <div class="card">

                    <div class="card-block">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>SUB PROYECTO</label>

                                    <select id="selectSubProy" name="selectSubProy" class="select2"
                                            onchange="filtrarTablaInterna()" multiple>
                                        <option>&nbsp;</option>
                                        <?php
                                        foreach ($listaSubProy->result() as $row) {
                                            ?>
                                            <option value="<?php echo $row->subProyectoDesc ?>"><?php echo $row->subProyectoDesc ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>EECC</label>

                                    <select id="selectEECC" name="selectEECC" class="select2"
                                            onchange="filtrarTablaInterna()">
                                        <option>&nbsp;</option>
                                        <?php
                                        foreach ($listaEECC->result() as $row) {
                                            ?>
                                            <option value="<?php echo $row->empresaColabDesc ?>"><?php echo $row->empresaColabDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>ZONAL</label>

                                    <select id="selectZonal" name="selectZonal" class="select2"
                                            onchange="filtrarTablaInterna()" multiple>
                                        <option>&nbsp;</option>
                                        <?php
                                        foreach ($listaZonal->result() as $row) {
                                            ?>
                                            <option value="<?php echo $row->zonalDesc ?>"><?php echo $row->zonalDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <!--
                            <div class="col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label>CON ITEM PLAN</label>

                                    <select id="selectHasItemPlan" name="selectHasItemPlan" class="select2" onchange="filtrarTablaInterna()">
                                        <option>&nbsp;</option>
                                    <option selected value="SI">SI</option>
                                    <option value="NO">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">
                                <div class="form-group">
                                    <label>ESTADO</label>

                                    <select id="selectEstado" name="selectEstado" class="select2" onchange="filtrarTablaInterna()" multiple>
                                        <option>&nbsp;</option>
                                    <option value="01">01</option>

                                    <option value="003">003</option>
                                    </select>
                                </div>
                            </div>
                            -->
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>MES PREVISTO EJECUCION</label>

                                    <select id="selectMesEjec" name="selectMesEjec" class="select2"
                                            onchange="filtrarTablaInterna()">
                                        <option>&nbsp;</option>
                                        <option value="1">ENERO</option>
                                        <option value="2">FEBRERO</option>
                                        <option value="3">MARZO</option>
                                        <option value="4">ABRIL</option>
                                        <option value="5">MAYO</option>
                                        <option value="6">JUNIO</option>
                                        <option value="7">JULIO</option>
                                        <option value="8">AGOSTO</option>
                                        <option value="9">SEPTIEMBRE</option>
                                        <option value="10">OCTUBRE</option>
                                        <option value="11">NOVIEMBRE</option>
                                        <option value="12">DICIEMBRE</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>AREA</label>

                                    <select id="selectArea" name="selectArea" class="select2"
                                            onchange="filtrarTablaInterna()">
                                        <option>&nbsp;</option>
                                        <option value="MAT">MAT</option>
                                        <option value="MO">MO</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>USUARIO</label>
                                    <select id="selectUsuario" name="selectUsuario" class="select2"
                                            onchange="filtrarTablaInterna()" multiple>
                                        <option>&nbsp;</option>
                                        <?php
                                        foreach ($listUsuarios as $row) {
                                            ?>
                                            <option value="<?php echo $row->id_usuario ?>"><?php echo $row->nombre ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="contTabla" class="table-responsive">
                            <?php echo $tablaasigGrafoInterna ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Small -->
    <div class="modal fade" id="modalVR" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-title" style="background:#0154a0;color:white;height:30px" align="center">
                    SELECCIONAR UNA OPCI&Oacute;N                          
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <button id="botonContinuar" type="button" onclick="asignarGrafoInterna(this, 1)" class="btn btn-success">
                                validar
                            </button>
                        </div>
                        <div class="form-group container-fluid">
                            <button id="botonRechazar" type="button" onclick="asignarGrafoInterna(this, 2)" class="btn btn-danger">
                                Rechazar
                            </button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
                        </div>             
                    </div>           
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalConsulta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-title" style="background:#0154a0;color:white;height:50px;font-size:25px" align="center">
                    COTIZACI&Oacute;N                          
                </div>
                <div class="modal-body">
                <div id="contTablaCotizacion" class="table-responsive">
                </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn" data-dismiss="modal">Cerrar</button>
                    </div> 
                </div>
            </div>
        </div>
    </div>
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
        var itemplanGlobal = null;
        var ptrGlobal      = null;
        function consultarCotizacionPtr(btn) {
            ptrGlobal          = btn.data('ptr');
            itemplanGlobal = btn.data('itemplan');
            if(ptrGlobal == '' || ptrGlobal == null) {
                return;
            }

            if(itemplanGlobal == null || itemplanGlobal == '') {
                return;
            }
            $.ajax({
                type : 'POST',
                url  : 'getCotizacionPtr',
                data : { 'ptr'      : ptrGlobal, 
                         'itemplan' : itemplanGlobal }
            }).done(function(data){
                data = JSON.parse(data);
                modal('modalConsulta');
                $('#contTablaCotizacion').html(data.tablaCotizacion);
                var tableCotizacion = data.tablaCotizacion;
            });
        }
        
        function modificarPTRPI(component) {
            var itemplan     = $(component).attr('data-item'); 
            var ptr          = $(component).attr('data-ptr'); 
            var flgRechazado = $(component).attr('data-flg_rechazado'); 
            
            window.location.href = "<?php echo base_url() . 'editPtrPI?item=';?>" + itemplan + "&&ptr=" + ptr+"&&flg_rechazado="+flgRechazado;
        }
        function filtrarTablaInterna() {
            var subProy   = $.trim($('#selectSubProy').val());
            var eecc      = $.trim($('#selectEECC').val());
            var zonal     = $.trim($('#selectZonal').val());
            var item      = $.trim($('#selectHasItemPlan').val());
            var mes       = $.trim($('#selectMesEjec').val());
            var area      = $.trim($('#selectArea option:selected').val());
            var estado    = $.trim($('#selectEstado option:selected').val());
            var idUsuario = $.trim($('#selectUsuario option:selected').val());

            $.ajax({
                type: 'POST',
                'url': 'filtrarTablaRechazadas',
                data: {
                    subProy   : subProy,
                    eecc      : eecc,
                    zonal     : zonal,
                    item      : item,
                    mes       : mes,
                    area      : area,
                    estado    : estado,
                    idUsuario : idUsuario
                },
                'async': false
            })
                .done(function (data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTabla').html(data.tablaasigGrafoInterna)
                        initDataTable('#data-table');

                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                    }
                });
        }
    </script>